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
    header("Location: ../../views/users/user-membership.php");
    exit();
}

$user_id    = $_SESSION["user_id"];
$id         = trim($_POST["id"]         ?? "");
$type       = trim($_POST["type"]       ?? "");
$fee        = trim($_POST["fee"]        ?? "");
$start_date = trim($_POST["start_date"] ?? "");
$end_date   = trim($_POST["end_date"]   ?? "");

// ── Validation ──────────────────────────────────────────────
if (!$id || !is_numeric($id)) {
    $_SESSION["error"] = "Invalid membership ID.";
    header("Location: ../../views/users/user-membership.php");
    exit();
}

$allowed_types = ["Basic", "Premium", "VIP"];

if (!in_array($type, $allowed_types)) {
    $_SESSION["error"] = "Invalid membership type.";
    header("Location: ../../views/users/user-membership.php");
    exit();
}

if (!$start_date || !$end_date) {
    $_SESSION["error"] = "Start date and end date are required.";
    header("Location: ../../views/users/user-membership.php");
    exit();
}

if (!strtotime($start_date) || !strtotime($end_date)) {
    $_SESSION["error"] = "Invalid date format.";
    header("Location: ../../views/users/user-membership.php");
    exit();
}

if (strtotime($end_date) <= strtotime($start_date)) {
    $_SESSION["error"] = "End date must be after the start date.";
    header("Location: ../../views/users/user-membership.php");
    exit();
}

if (strtotime($start_date) < strtotime(date("Y-m-d"))) {
    $_SESSION["error"] = "Start date cannot be in the past.";
    header("Location: ../../views/users/user-membership.php");
    exit();
}

if (!is_numeric($fee) || $fee < 0) {
    $_SESSION["error"] = "Invalid fee amount.";
    header("Location: ../../views/users/user-membership.php");
    exit();
}

// ── Ownership + status guard: only pending rows may be edited ─
$database = new Database();
$conn     = $database->connection();

$check = $conn->prepare("
    SELECT id FROM memberships
    WHERE id = ?
      AND member_id = ?
      AND status = 'pending'
    LIMIT 1
");
$check->bind_param("ii", $id, $user_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $check->close();
    $conn->close();
    $_SESSION["error"] = "Membership not found or cannot be edited.";
    header("Location: ../../views/users/user-membership.php");
    exit();
}
$check->close();

// ── Update ───────────────────────────────────────────────────
$stmt = $conn->prepare("
    UPDATE memberships
    SET type = ?, fee = ?, start_date = ?, end_date = ?
    WHERE id = ?
      AND member_id = ?
      AND status = 'pending'
");
$stmt->bind_param("sdssii", $type, $fee, $start_date, $end_date, $id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $_SESSION["success"] = "Your membership request has been updated successfully.";
} else {
    $_SESSION["error"] = "Failed to update your request. Please try again.";
}

$stmt->close();
$conn->close();

header("Location: ../../views/users /user-membership.php");
exit();