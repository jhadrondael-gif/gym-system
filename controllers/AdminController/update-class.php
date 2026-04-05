<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    $_SESSION["error"] = "Unauthorized.";
    header("Location: ../../classes.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["error"] = "Invalid request method.";
    header("Location: ../../classes.php");
    exit();
}

$id          = intval($_POST["id"]          ?? 0);
$name        = trim($_POST["name"]          ?? "");
$instructor  = trim($_POST["instructor"]    ?? "");
$day         = trim($_POST["day"]           ?? "");
$time        = trim($_POST["time"]          ?? "");
$description = trim($_POST["description"]   ?? "");

$allowed_days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

if (!$id || !$name || !$instructor || !$day || !$time) {
    $_SESSION["error"] = "All required fields must be filled.";
    header("Location: ../../classes.php");
    exit();
}

if (!in_array($day, $allowed_days)) {
    $_SESSION["error"] = "Invalid day selected.";
    header("Location: ../../classes.php");
    exit();
}

$database   = new Database();
$connection = $database->connection();

$stmt = $connection->prepare("
    UPDATE classes
    SET name = ?, instructor = ?, description = ?, day = ?, time = ?
    WHERE id = ?
");
$stmt->bind_param("sssssi", $name, $instructor, $description, $day, $time, $id);

if ($stmt->execute()) {
    $_SESSION["success"] = "Class updated successfully.";
} else {
    $_SESSION["error"] = "Failed to update class. Please try again.";
}

$stmt->close();
$connection->close();

header("Location: ../../classes.php");
exit();