<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    $response = ["error" => "Unauthorized."];
    exit();
}

$database   = new Database();
$connection = $database->connection();

// Join with members to get the member's full name
$stmt = $connection->prepare("
    SELECT 
        ms.id,
        ms.member_id,
        CONCAT(m.first_name, ' ', m.last_name) AS member_name,
        ms.type,
        ms.start_date,
        ms.end_date,
        ms.status,
        ms.fee
    FROM memberships ms
    JOIN members m ON ms.member_id = m.id
    ORDER BY ms.id DESC
");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response = ["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)];
} else {
    $response = ["success" => true, "data" => []];
}

$stmt->close();
$connection->close();