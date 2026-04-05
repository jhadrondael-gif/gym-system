<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    $response = ["error" => "Unauthorized."];
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = trim($_POST["id"]);

    $database   = new Database();
    $connection = $database->connection();

    $stmt = $connection->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Member deleted successfully.";
    } else {
        $_SESSION["error"] = "Failed to delete member. Please try again.";
    }

    $stmt->close();
    $connection->close();

    header("Location: /gym-system/member.php");
    exit();
}