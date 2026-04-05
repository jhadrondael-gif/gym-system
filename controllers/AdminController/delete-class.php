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

$id = intval($_POST["id"] ?? 0);

if (!$id) {
    $_SESSION["error"] = "Invalid class.";
    header("Location: ../../classes.php");
    exit();
}

$database   = new Database();
$connection = $database->connection();

$stmt = $connection->prepare("DELETE FROM classes WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION["success"] = "Class deleted successfully.";
} else {
    $_SESSION["error"] = "Failed to delete class. Please try again.";
}

$stmt->close();
$connection->close();

header("Location: ../../classes.php");
exit();