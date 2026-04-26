<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$database = new Database();
$conn = $database->connection();

// --- All memberships for this user (history) ---
$stmt = $conn->prepare("
    SELECT * FROM memberships
    WHERE member_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$memberships = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// --- Active membership (latest active) ---
$active_membership = null;
foreach ($memberships as $ms) {
    if (strtolower($ms['status']) === 'active') {
        $active_membership = $ms;
        break;
    }
}

// --- Pending membership request ---
$pending_membership = null;
foreach ($memberships as $ms) {
    if (strtolower($ms['status']) === 'pending') {
        $pending_membership = $ms;
        break;
    }
}

$conn->close();

// Days left helper
function days_left($end_date) {
    $today = new DateTime();
    $end   = new DateTime($end_date);
    $diff  = $today->diff($end);
    return $diff->invert ? 0 : $diff->days;
}
?>