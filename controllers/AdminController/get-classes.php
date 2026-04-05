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
        c.id,
        c.name,
        c.instructor,
        c.description,
        c.day,
        TIME_FORMAT(c.time, '%h:%i %p') AS time,
        COUNT(e.id) AS enrolled_count
    FROM classes c
    LEFT JOIN enrollments e ON c.id = e.class_id
    GROUP BY c.id
    ORDER BY c.day ASC, c.time ASC
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