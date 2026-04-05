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

    $id             = trim($_POST["id"]);
    $first_name     = trim($_POST["first_name"]);
    $last_name      = trim($_POST["last_name"]);
    $gender         = trim($_POST["gender"]);
    $birthdate      = trim($_POST["birthdate"]);
    $contact_number = trim($_POST["contact_number"]);
    $email          = trim($_POST["email"]);
    $status         = trim($_POST["status"]);
    $role           = trim($_POST["role"]);

    $database   = new Database();
    $connection = $database->connection();

    // Check if email is taken by another member
    $stmt = $connection->prepare("SELECT id FROM members WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION["error"] = "Email is already used by another member.";
        $stmt->close();
        $connection->close();
        header("Location: ../../member.php");
        exit();
    }

    $stmt->close();

    // Update without changing password
    $stmt = $connection->prepare("UPDATE members SET first_name = ?, last_name = ?, gender = ?, birthdate = ?, contact_number = ?, email = ?, status = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssssssi", $first_name, $last_name, $gender, $birthdate, $contact_number, $email, $status, $role, $id);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Member updated successfully.";
    } else {
        $_SESSION["error"] = "Failed to update member. Please try again.";
    }

    $stmt->close();
    $connection->close();

    header("Location: ../../member.php");
    exit();
}