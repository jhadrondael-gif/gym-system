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

    $first_name     = trim($_POST["first_name"]);
    $last_name      = trim($_POST["last_name"]);
    $gender         = trim($_POST["gender"]);
    $birthdate      = trim($_POST["birthdate"]);
    $contact_number = trim($_POST["contact_number"]);
    $email          = trim($_POST["email"]);
    $password       = trim($_POST["password"]);
    $status         = trim($_POST["status"]);
    $role           = trim($_POST["role"]);

    $database   = new Database();
    $connection = $database->connection();

    // Check if email already exists
    $stmt = $connection->prepare("SELECT id FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION["error"] = "Email is already registered.";
        $stmt->close();
        $connection->close();
        header("Location: /gym-system/member.php");
        exit();
    }

    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $connection->prepare("INSERT INTO members (first_name, last_name, gender, role, birthdate, contact_number, email, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $first_name, $last_name, $gender, $role, $birthdate, $contact_number, $email, $hashedPassword, $status);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Member created successfully.";
    } else {
        $_SESSION["error"] = "Failed to create member. Please try again.";
    }

    $stmt->close();
    $connection->close();

    header("Location: /gym-system/member.php");
    exit();
}