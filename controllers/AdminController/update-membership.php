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

    $id         = trim($_POST["id"]);
    $member_id  = trim($_POST["member_id"]);
    $type       = trim($_POST["type"]);
    $start_date = trim($_POST["start_date"]);
    $end_date   = trim($_POST["end_date"]);
    $status     = trim($_POST["status"]);
    $fee        = trim($_POST["fee"]);

    $database   = new Database();
    $connection = $database->connection();

    $stmt = $connection->prepare("UPDATE memberships SET member_id = ?, type = ?, start_date = ?, end_date = ?, status = ?, fee = ? WHERE id = ?");
    $stmt->bind_param("issssdi", $member_id, $type, $start_date, $end_date, $status, $fee, $id);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Membership updated successfully.";
    } else {
        $_SESSION["error"] = "Failed to update membership. Please try again.";
    }

    $stmt->close();
    $connection->close();

    header("Location: /gym-system/membership.php");
    exit();
}