<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    $_SESSION["error"] = "Unauthorized.";
    header("Location: ../../enrollments.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["error"] = "Invalid request method.";
    header("Location: ../../enrollments.php");
    exit();
}

$member_id = intval($_POST["member_id"] ?? 0);
$class_id  = intval($_POST["class_id"]  ?? 0);
$status    = trim($_POST["status"]      ?? "pending");

$allowed_statuses = ["pending", "approved", "rejected"];

if (!$member_id || !$class_id) {
    $_SESSION["error"] = "Member and class are required.";
    header("Location: ../../enrollments.php");
    exit();
}

if (!in_array($status, $allowed_statuses)) {
    $_SESSION["error"] = "Invalid status.";
    header("Location: ../../enrollments.php");
    exit();
}

$database   = new Database();
$connection = $database->connection();

// Check for duplicate
$stmt = $connection->prepare("
    SELECT id FROM enrollments
    WHERE member_id = ? AND class_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $member_id, $class_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION["error"] = "This member is already enrolled in that class.";
    $stmt->close();
    $connection->close();
    header("Location: ../../enrollments.php");
    exit();
}

$stmt->close();

$initiated_by = "admin";
$stmt = $connection->prepare("
    INSERT INTO enrollments (class_id, member_id, status, initiated_by)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("iiss", $class_id, $member_id, $status, $initiated_by);

if ($stmt->execute()) {
    $_SESSION["success"] = "Enrollment created successfully.";
} else {
    $_SESSION["error"] = "Failed to create enrollment. Please try again.";
}

$stmt->close();
$connection->close();

header("Location: ../../enrollments.php");
exit();