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

$name        = trim($_POST["name"]        ?? "");
$instructor  = trim($_POST["instructor"]  ?? "");
$day         = trim($_POST["day"]         ?? "");
$time        = trim($_POST["time"]        ?? "");
$description = trim($_POST["description"] ?? "");

$allowed_days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

if (!$name || !$instructor || !$day || !$time) {
    $_SESSION["error"] = "Name, instructor, day, and time are required.";
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
    INSERT INTO classes (name, instructor, description, day, time)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("sssss", $name, $instructor, $description, $day, $time);

if ($stmt->execute()) {
    $_SESSION["success"] = "Class \"$name\" created successfully.";
} else {
    $_SESSION["error"] = "Failed to create class. Please try again.";
}

$stmt->close();
$connection->close();

header("Location: ../../classes.php");
exit();