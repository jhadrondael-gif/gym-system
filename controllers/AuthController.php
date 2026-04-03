<?php
session_start();
require("../db.php");

$database = new Database();

if (isset($_SESSION["user"])) {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"] ?? "" ;

    if ($action === "login") {
        handleLogin($database);
    } elseif ($action == "register") {
        handleRegister($database);
    }

    $stmt->close();
    $connection->close();

}

function handleLogin($database) {

    
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $connection = $database->connection();

    $stmt = $connection->prepare("SELECT id, email, password FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows==1) {
        $row =  $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            $_SESSION["user"] = $row["email"];
            $_SESSION["user_id"] = $row["id"];
            header("Location: ../dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
            header("Location: login.php");
            exit();
        }
    } else {
        $error = "Invalid email or password";
        header("Location: login.php");
        exit();
    }

}

function handleRegister($database) {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $gender = $_POST["gender"];
    $role = "user";
    $birthdate = $_POST["birthdate"];
    $contact_number = $_POST["contact_number"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $connection = $database->connection();

    $stmt = $connection->prepare("SELECT id FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION["error"] = "Email is already registered.";
        header("Location: ../register.php");
        exit();
    }

    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $connection->prepare("INSERT INTO members (first_name, last_name, gender, role, birthdate, contact_number, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdsss", $first_name, $last_name, $gender, $role, $birthdate, $contact_number, $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Account created! You can now log in.";
        header("Location: ../login.php");
        exit();
    } else {
        $_SESSION["error"] = "Registration failed. Please try again.";
        header("Location: ../register.php");
        exit();
    }

    $stmt->close();
    $connection->close();

}