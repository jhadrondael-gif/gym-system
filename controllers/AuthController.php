<?php
session_start();
require("../db.php");

$database = new Database();
session_unset();

// if ($row["role"] === "admin") {
//     $_SESSION["admin"]    = $row["email"];
//     $_SESSION["admin_id"] = $row["id"];
//     header("Location: ../dashboard.php");
//     exit();
// } elseif ($row["role"] === "user") {
//     $_SESSION["user"]    = $row["email"];
//     $_SESSION["user_id"] = $row["id"];
//     header("Location: ../views/users/user-dashboard.php");
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"] ?? "";

    if ($action === "login") {
        handleLogin($database);
    } elseif ($action === "register") {
        handleRegister($database);
    }

}

function handleLogin($database) {

    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $connection = $database->connection();

    $stmt = $connection->prepare("SELECT id, email, password, role FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (!password_verify($password, $row["password"])) {
            $_SESSION["error"] = "Invalid email or password.";
            $stmt->close();
            $connection->close();
            header("Location: ../login.php");
            exit();
        }

        $stmt->close();
        $connection->close();

        if ($row["role"] === "admin") {            
            $_SESSION["admin"]    = $row["email"];
            $_SESSION["admin_id"] = $row["id"];
            header("Location: ../dashboard.php");
            exit();
        } elseif ($row["role"] === "user") {            
            $_SESSION["user"]    = $row["email"];
            $_SESSION["user_id"] = $row["id"];
            header("Location: ../views/users/user-dashboard.php");
            exit();
        }

    } else {
        $_SESSION["error"] = "Invalid email or password.";
        $stmt->close();
        $connection->close();
        header("Location: ../login.php");
        exit();
    }
}

function handleRegister($database) {

    $first_name     = trim($_POST["first_name"]);
    $last_name      = trim($_POST["last_name"]);
    $gender         = trim($_POST["gender"]);
    $role           = "user";
    $birthdate      = trim($_POST["birthdate"]);
    $contact_number = trim($_POST["contact_number"]);
    $email          = trim($_POST["email"]);
    $password       = trim($_POST["password"]);
    $status         = "active";

    $connection = $database->connection();

    $stmt = $connection->prepare("SELECT id FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION["error"] = "Email is already registered.";
        $stmt->close();
        $connection->close();
        header("Location: ../registration.php");
        exit();
    }

    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $connection->prepare("INSERT INTO members (first_name, last_name, gender, role, birthdate, contact_number, email, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $first_name, $last_name, $gender, $role, $birthdate, $contact_number, $email, $hashedPassword, $status);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Account created! You can now log in.";
        $stmt->close();
        $connection->close();
        header("Location: ../login.php");
        exit();
    } else {
        $_SESSION["error"] = "Registration failed. Please try again.";
        $stmt->close();
        $connection->close();
        header("Location: ../registration.php");
        exit();
    }
}