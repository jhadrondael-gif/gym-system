<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    $_SESSION["error"] = "Unauthorized.";
    header("Location: ../../membership.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["error"] = "Invalid request method.";
    header("Location: ../../membership.php");
    exit();
}

$member_id  = trim($_POST["member_id"]  ?? "");
$type       = trim($_POST["type"]       ?? "");
$status     = trim($_POST["status"]     ?? "");
$start_date = trim($_POST["start_date"] ?? "");
$end_date   = trim($_POST["end_date"]   ?? "");
$fee        = trim($_POST["fee"]        ?? "");

// Validation
if (!$member_id || !$type || !$status || !$start_date || !$end_date || $fee === "") {
    $_SESSION["error"] = "All fields are required.";
    header("Location: ../../membership.php");
    exit();
}

$allowed_types    = ["Basic", "Premium", "VIP"];
$allowed_statuses = ["active", "expired", "cancelled"];

if (!in_array($type, $allowed_types)) {
    $_SESSION["error"] = "Invalid membership type.";
    header("Location: ../../membership.php");
    exit();
}

if (!in_array($status, $allowed_statuses)) {
    $_SESSION["error"] = "Invalid membership status.";
    header("Location: ../../membership.php");
    exit();
}

if (!strtotime($start_date) || !strtotime($end_date)) {
    $_SESSION["error"] = "Invalid date format.";
    header("Location: ../../membership.php");
    exit();
}

if (strtotime($end_date) < strtotime($start_date)) {
    $_SESSION["error"] = "End date cannot be before start date.";
    header("Location: ../../membership.php");
    exit();
}

if (!is_numeric($fee) || $fee < 0) {
    $_SESSION["error"] = "Fee must be a valid non-negative number.";
    header("Location: ../../membership.php");
    exit();
}

$database   = new Database();
$connection = $database->connection();

$stmt = $connection->prepare("
    INSERT INTO memberships (member_id, type, start_date, end_date, status, fee)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("issssd", $member_id, $type, $start_date, $end_date, $status, $fee);

if ($stmt->execute()) {
    $_SESSION["success"] = "Membership created successfully.";
} else {
    $_SESSION["error"] = "Failed to create membership. Please try again.";
}

$stmt->close();
$connection->close();

header("Location: ../../membership.php");
exit();