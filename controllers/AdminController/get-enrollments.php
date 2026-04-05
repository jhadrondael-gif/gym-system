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
        e.id,
        e.class_id,
        e.member_id,
        CONCAT(m.first_name, ' ', m.last_name) AS member_name,
        c.name        AS class_name,
        c.instructor,
        c.day,
        TIME_FORMAT(c.time, '%h:%i %p') AS class_time,
        e.status,
        e.initiated_by,
        DATE_FORMAT(e.enrolled_at, '%M %d, %Y') AS enrolled_at
    FROM enrollments e
    JOIN members m ON e.member_id = m.id
    JOIN classes  c ON e.class_id  = c.id
    ORDER BY
        FIELD(e.status, 'pending', 'approved', 'rejected'),
        e.enrolled_at DESC
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