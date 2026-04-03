<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <!-- Icons (Lucide alternative using CDN) -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    /* Background */
    .bg {
      position: absolute;
      inset: 0;
      background: url('https://images.unsplash.com/photo-1763652387673-71b75ee71a24?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080') center/cover no-repeat;
      z-index: -2;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.4);
      z-index: -1;
    }

    /* Card */
    .card {
      width: 100%;
      max-width: 400px;
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .card h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
    }

    input {
      width: 100%;
      padding: 10px 10px 10px 35px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .actions {
      text-align: right;
      margin-bottom: 15px;
    }

    .actions a {
      font-size: 14px;
      color: #007bff;
      text-decoration: none;
    }

    .actions a:hover {
      text-decoration: underline;
    }

    button {
      width: 100%;
      padding: 10px;
      border: none;
      background: #007bff;
      color: white;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background: #0056b3;
    }
  </style>
</head>

<body>

<div class="bg"></div>
<div class="overlay"></div>

        <?php if (isset($_SESSION["error"])): ?>
            <div class="error">
                <?php
                    echo htmlspecialchars($_SESSION["error"]);
                    unset($_SESSION["error"]);
                ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <form action="controllers/AuthController.php" method="POST">
                <h2>Login</h2>

                <div class="form-group">
                <input type="hidden" name="action" value="login">
                <div class="input-box">
                    <input type="text" name="email" placeholder="Email" required>
                </div>

                <label>Password</label>
                <div class="input-wrapper">
                    <i data-lucide="lock"></i>
                    <input type="password" id="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="actions">
            <a href="#">Register</a>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>



</body>
</html>