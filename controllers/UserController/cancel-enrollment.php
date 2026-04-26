<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../views/users/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../views/users/user-classes.php");
    exit();
}

$user_id       = $_SESSION["user_id"];
$enrollment_id = isset($_POST["enrollment_id"]) ? (int) $_POST["enrollment_id"] : 0;

if (!$enrollment_id) {
    $_SESSION["error"] = "Invalid enrollment.";
    header("Location: ../../views/users/user-classes.php");
    exit();
}

$database = new Database();
$conn     = $database->connection();

// ── Ownership + status guard: only active or pending rows ────
// Users may withdraw a pending request OR unenroll from an active enrollment.
// Rejected enrollments are already closed — no action needed.
$check = $conn->prepare("
    SELECT e.id, e.status, c.name AS class_name
    FROM enrollments e
    JOIN classes c ON c.id = e.class_id
    WHERE e.id        = ?
      AND e.member_id = ?
      AND e.status IN ('approved', 'pending')
    LIMIT 1
");
$check->bind_param("ii", $enrollment_id, $user_id);
$check->execute();
$enrollment = $check->get_result()->fetch_assoc();
$check->close();

if (!$enrollment) {
    $conn->close();
    $_SESSION["error"] = "Enrollment not found or cannot be cancelled.";
    header("Location: ../../views/users/user-classes.php");
    exit();
}

// ── Soft-delete: set status to 'cancelled' ───────────────────
$stmt = $conn->prepare("
    UPDATE enrollments
    SET status = 'cancelled'
    WHERE id        = ?
      AND member_id = ?
      AND status IN ('approved', 'pending')
");
$stmt->bind_param("ii", $enrollment_id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $class_name = htmlspecialchars($enrollment["class_name"]);
    $was_pending = strtolower($enrollment["status"]) === "pending";

    $_SESSION["success"] = $was_pending
        ? "Your enrollment request for <strong>{$class_name}</strong> has been withdrawn."
        : "You have unenrolled from <strong>{$class_name}</strong>.";
} else {
    $_SESSION["error"] = "Failed to cancel your enrollment. Please try again.";
}

$stmt->close();
$conn->close();

header("Location: ../../views/users/user-classes.php");
exit();