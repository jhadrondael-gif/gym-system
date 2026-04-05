<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

header("Content-Type: application/json");

if (!isset($_SESSION["admin"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized."]);
    exit();
}

$input  = json_decode(file_get_contents("php://input"), true);
$id     = intval($input["id"]     ?? 0);
$status = trim($input["status"]   ?? "");

$allowed = ["pending", "approved", "rejected"];

if (!$id || !in_array($status, $allowed)) {
    echo json_encode(["success" => false, "message" => "Invalid data."]);
    exit();
}

$database   = new Database();
$connection = $database->connection();

$stmt = $connection->prepare("UPDATE enrollments SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Status updated to $status."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update status."]);
}

$stmt->close();
$connection->close();