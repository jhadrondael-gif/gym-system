<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

header("Content-Type: application/json");

if (!isset($_SESSION["admin"])) {
    echo json_encode(["error" => "Unauthorized."]);
    exit();
}

$class_id = intval($_GET["class_id"] ?? 0);

if (!$class_id) {
    echo json_encode(["success" => false, "data" => []]);
    exit();
}

$database   = new Database();
$connection = $database->connection();

$stmt = $connection->prepare("
    SELECT
        e.id,
        e.status,
        CONCAT(m.first_name, ' ', m.last_name) AS member_name,
        m.email,
        DATE_FORMAT(e.enrolled_at, '%M %d, %Y') AS enrolled_at
    FROM enrollments e
    JOIN members m ON e.member_id = m.id
    WHERE e.class_id = ?
      AND e.status IN ('approved', 'pending')
    ORDER BY m.first_name ASC
");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result  = $stmt->get_result();
$members = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(["success" => true, "data" => $members]);

$stmt->close();
$connection->close();