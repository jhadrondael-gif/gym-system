<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gym Registration</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #04a1d1, #106dc4);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0px 5px 10px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #ff4d88;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background: #e6005c;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Gym Registration</h2>

        <form action="controllers/AuthController.php" method="POST">

            <input type="hidden" name="action" value="register">

            <input type="text" name="first_name" placeholder="First Name" required>

            <input type="text" name="last_name" placeholder="Last Name" required>

            <select name="gender" required>
                <option value="">Select Gender</option>
                <option>Male</option>
                <option>Female</option>
            </select>

            <select name="role">
                <option value="">Select Role</option>
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>


            <input type="date" name="birthdate" required>

            <input type="text" name="contact_number" placeholder="Contact Number" required>

            <input type="email" name="email" placeholder="Email" required>

            <input type="password" name="password" placeholder="Password" required>

            <input type="password" name="Confirm_password" placeholder="Confirm_Password" required>


            <button type="submit" class="btn">Register</button>

        </form>
    </div>

</body>
</html>