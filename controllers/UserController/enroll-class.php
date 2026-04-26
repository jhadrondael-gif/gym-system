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

$user_id  = $_SESSION["user_id"];
$class_id = isset($_POST["class_id"]) ? (int) $_POST["class_id"] : 0;

if (!$class_id) {
    $_SESSION["error"] = "Invalid class.";
    header("Location: ../../views/users/user-classes.php");
    exit();
}

$database = new Database();
$conn     = $database->connection();

// ── Guard: class must exist ───────────────────────────────────
$check = $conn->prepare("SELECT id, name FROM classes WHERE id = ? LIMIT 1");
$check->bind_param("i", $class_id);
$check->execute();
$class = $check->get_result()->fetch_assoc();
$check->close();

if (!$class) {
    $conn->close();
    $_SESSION["error"] = "Class not found.";
    header("Location: ../../views/users/user-classes.php");
    exit();
}

// ── Guard: not already enrolled or pending ────────────────────
$stmt = $conn->prepare("
    SELECT id, status FROM enrollments
    WHERE class_id = ? AND member_id = ? AND status != 'rejected'
    LIMIT 1
");
$stmt->bind_param("ii", $class_id, $user_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    $conn->close();
    $msg = strtolower($existing["status"]) === "pending"
        ? "You already have a pending enrollment request for this class."
        : "You are already enrolled in this class.";
    $_SESSION["error"] = $msg;
    header("Location: ../../views/users/user-classes.php");
    exit();
}

// ── Insert ────────────────────────────────────────────────────
$insert = $conn->prepare("
    INSERT INTO enrollments (class_id, member_id, status, initiated_by)
    VALUES (?, ?, 'pending', 'member')
");
$insert->bind_param("ii", $class_id, $user_id);

if ($insert->execute()) {
    $_SESSION["success"] = "Enrollment request submitted for <strong>{$class['name']}</strong>. Awaiting admin approval.";
} else {
    $_SESSION["error"] = "Something went wrong. Please try again.";
}

$insert->close();
$conn->close();

header("Location: ../../views/users/user-classes.php");
exit();