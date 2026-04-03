<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>

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

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
        }

        .input-box {
            margin: 10px 0;
        }

        .input-box input {
            width: 100%;
            padding: 10px;
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
            margin-top: 15px;
        }

        .btn:hover {
            background: #e6005c;
        }

        .footer {
            margin-top: 10px;
            font-size: 12px;
        }

        .footer a {
            color: #ff4d88;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>Login</h2>

        <form action="dashboard.php" method="POST">
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="footer">
            <p>Don't have an account? <a href="registration.php">Register</a></p>
        </div>
    </div>

</body>
</html>