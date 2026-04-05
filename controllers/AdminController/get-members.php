<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    $response = ["error" => "Unauthorized."];
    exit();
}

$database = new Database();
$connection = $database->connection();

$stmt = $connection->prepare("SELECT * FROM members");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $members = $result->fetch_all(MYSQLI_ASSOC);
    $response = ["success" => true, "data" => $members];
} else {
    $response = ["success" => true, "data" => []];
}

$stmt->close();
$connection->close();