<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    $_SESSION["error"] = "Unauthorized.";
    header("Location: ../../enrollment.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["error"] = "Invalid request method.";
    header("Location: ../../enrollment.php");
    exit();
}

$id = intval($_POST["id"] ?? 0);

if (!$id) {
    $_SESSION["error"] = "Invalid enrollment.";
    header("Location: ../../enrollment.php");
    exit();
}

$database   = new Database();
$connection = $database->connection();

$stmt = $connection->prepare("DELETE FROM enrollments WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION["success"] = "Enrollment removed successfully.";
} else {
    $_SESSION["error"] = "Failed to remove enrollment.";
}

$stmt->close();
$connection->close();

header("Location: ../../enrollment.php");
exit();