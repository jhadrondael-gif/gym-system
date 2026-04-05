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

$stmt = $connection->prepare("
    SELECT
        a.id,
        a.member_id,
        CONCAT(m.first_name, ' ', m.last_name) AS member_name,
        a.check_in,
        a.check_out
    FROM attendance a
    JOIN members m ON a.member_id = m.id
    ORDER BY a.check_in DESC
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