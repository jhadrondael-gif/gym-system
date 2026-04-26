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
   <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * *, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

:root {
    --yellow: #c89b00;
    --yellow-hover: #b08800;
    --yellow-bright: #f5c800;
    --dark-bg: #1a1200;
    --card-bg: #1e1a0e;
    --input-bg: #2a240f;
    --border: #c89b00;
    --text: #c89b00;
    --muted: #6b5e30;
}

body {
    margin: 0;
    padding: 20px;
    font-family: 'Barlow', sans-serif;
    background-color: #1c1600;
    background-image:
        radial-gradient(ellipse at 20% 50%, rgba(60, 45, 0, 0.8) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(40, 30, 0, 0.7) 0%, transparent 50%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.06'/%3E%3C/svg%3E");
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

h2 {
    text-align: center;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 2.2rem;
    letter-spacing: 3px;
    color: var(--yellow-bright);
    margin-bottom: 24px;
}

.card {
  padding: 50px;
  background: var(--card-bg); 
  border-radius: 12px;
}

.field-group {
    margin-bottom: 14px;
}

label {
    display: block;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--yellow);
    margin-bottom: 5px;
}

.input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.input-wrap svg {
    position: absolute;
    left: 12px;
    color: var(--yellow);
    opacity: 0.6;
    pointer-events: none;
}

input, select {
    width: 100%;
    padding: 10px 12px 10px 20px;
    background: var(--input-bg);
    border: 1.5px solid rgba(200, 155, 0, 0.3);
    border-radius: 7px;
    color: #c8a84a;
    font-family: 'Barlow', sans-serif;
    font-size: 0.92rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    appearance: none;
    -webkit-appearance: none;
}

form {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 10px; 
}

input::placeholder {
    color: var(--muted);
}

input:focus, select:focus {
    border-color: var(--yellow);
    box-shadow: 0 0 0 3px rgba(200, 155, 0, 0.1);
}

select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='7' viewBox='0 0 12 7'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23c89b00' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}

select option {
    background: #2a240f;
    color: #c8a84a;
}

.row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.register-link {
    text-align: right;
    margin-bottom: 16px;
}

.register-link a {
    color: var(--yellow);
    font-size: 0.85rem;
    text-decoration: none;
    opacity: 0.85;
    transition: opacity 0.2s;
}

.register-link a:hover {
    opacity: 1;
    text-decoration: underline;
}

.btn {
    width: 100%;
    padding: 8px;
    background: var(--yellow-bright);
    border: none;
    color: #111;
    border-radius: 7px;
    cursor: pointer;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.15rem;
    letter-spacing: 2px;
    transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
    margin-top: 6px;
    box-shadow: 0 4px 20px rgba(200, 155, 0, 0.3);
}

.btn:hover {
    background: var(--yellow-hover);
    box-shadow: 0 6px 28px rgba(200, 155, 0, 0.4);
}

.btn:active {
    transform: scale(0.98);
}

.actions {
  display: flex;
  flex-direction: column; 
}

.actions a {
  text-align: right;
  color: var(--yellow);
  text-decoration: none;
}


  </style>
</head>

<body>

<div class="bg"></div>
<div class="overlay"></div>

       
        <div class="card">
            <form action="controllers/AuthController.php" method="POST">
                <h2>Login</h2>

                <div class="form-group">
                  <label>Email</label> 
                  <input type="hidden" name="action" value="login">
                  <div class="input-box">
                      <input type="text" name="email" placeholder="Email" required>
                  </div>

                  <label>Password</label> 
                  <div class="input-wrapper">
                      <i data-lucide="lock"></i>
                      <input type="password" id="password" name="password" placeholder="••••••••" required>
                  </div>
                </div>

            <div class="actions">
              <a href="registration.php">Register</a>
              <button class="btn" type="submit">Login</button>
            </div>
        </form>
         <div class="card">
        <?php if (isset($_SESSION["error"])): ?>
            <div class="error">
                <?php
                    echo htmlspecialchars($_SESSION["error"]);
                    unset($_SESSION["error"]);
                ?>
            </div>
        <?php endif; ?>

    </div>



</body>
</html>