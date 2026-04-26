<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../views/UserView/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../views/UserView/user-membership.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$id      = trim($_POST["id"] ?? "");

// ── Validation ──────────────────────────────────────────────
if (!$id || !is_numeric($id)) {
    $_SESSION["error"] = "Invalid membership ID.";
    header("Location: ../../views/UserView/user-membership.php");
    exit();
}

// ── Ownership + status guard: only active or pending rows ────
$database = new Database();
$conn     = $database->connection();

$check = $conn->prepare("
    SELECT id, type, status FROM memberships
    WHERE id = ?
      AND member_id = ?
      AND status IN ('active', 'pending')
    LIMIT 1
");
$check->bind_param("ii", $id, $user_id);
$check->execute();
$result = $check->get_result();
$membership = $result->fetch_assoc();
$check->close();

if (!$membership) {
    $conn->close();
    $_SESSION["error"] = "Membership not found or cannot be cancelled.";
    header("Location: ../../views/UserView/user-membership.php");
    exit();
}

// ── Soft-delete: set status to 'cancelled' ───────────────────
$stmt = $conn->prepare("
    UPDATE memberships
    SET status = 'cancelled'
    WHERE id = ?
      AND member_id = ?
      AND status IN ('active', 'pending')
");
$stmt->bind_param("ii", $id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $was_pending = strtolower($membership["status"]) === "pending";
    $label       = htmlspecialchars($membership["type"]);

    $_SESSION["success"] = $was_pending
        ? "Your {$label} membership request has been withdrawn."
        : "Your {$label} membership has been cancelled.";
} else {
    $_SESSION["error"] = "Failed to cancel the membership. Please try again.";
}

$stmt->close();
$conn->close();

header("Location: ../../views/UserView/user-membership.php");
exit();