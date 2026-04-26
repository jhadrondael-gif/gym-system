<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – Gym System</title>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --yellow: #c89b00;
      --yellow-hover: #b08800;
      --yellow-bright: #f5c800;
      --dark-bg: #1c1600;
      --card-bg: #1e1a0e;
      --input-bg: #2a240f;
      --border: #c89b00;
      --text: #c89b00;
      --muted: #6b5e30;
    }

    body {
      font-family: 'Barlow', sans-serif;
      background-color: var(--dark-bg);
      background-image:
        radial-gradient(ellipse at 20% 50%, rgba(60,45,0,0.8) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(40,30,0,0.7) 0%, transparent 50%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.06'/%3E%3C/svg%3E");
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    /* ── Error alert ── */
    .alert-error {
      position: fixed;
      top: 16px;
      left: 50%;
      transform: translateX(-50%);
      background: #2a0a0a;
      border: 1px solid #ef444444;
      color: #ef4444;
      font-size: 13px;
      padding: 10px 20px;
      border-radius: 8px;
      z-index: 999;
      white-space: nowrap;
      max-width: calc(100vw - 32px);
      text-align: center;
      white-space: normal;
    }

    /* ── Card ── */
    .card {
      width: 100%;
      max-width: 420px;
      background: var(--card-bg);
      border-radius: 14px;
      padding: 44px 40px 40px;
      border: 1px solid rgba(200,155,0,0.15);
      box-shadow: 0 24px 60px rgba(0,0,0,0.5);
      animation: fadeIn 0.35s ease both;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    h2 {
      text-align: center;
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2.4rem;
      letter-spacing: 4px;
      color: var(--yellow-bright);
      margin-bottom: 8px;
    }

    .subtitle {
      text-align: center;
      font-size: 0.8rem;
      color: var(--muted);
      letter-spacing: 1px;
      margin-bottom: 28px;
    }

    /* ── Form ── */
    form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .field-group {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    label {
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: var(--yellow);
    }

    .input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-wrap svg {
      position: absolute;
      left: 12px;
      width: 15px;
      height: 15px;
      color: var(--yellow);
      opacity: 0.55;
      pointer-events: none;
      flex-shrink: 0;
    }

    input {
      width: 100%;
      padding: 11px 12px 11px 36px;
      background: var(--input-bg);
      border: 1.5px solid rgba(200,155,0,0.25);
      border-radius: 8px;
      color: #c8a84a;
      font-family: 'Barlow', sans-serif;
      font-size: 0.93rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      -webkit-appearance: none;
    }

    input::placeholder { color: var(--muted); }

    input:focus {
      border-color: var(--yellow);
      box-shadow: 0 0 0 3px rgba(200,155,0,0.12);
    }

    /* Password toggle */
    .toggle-pw {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      padding: 0;
      cursor: pointer;
      color: var(--muted);
      display: flex;
      align-items: center;
      transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--yellow); }
    .toggle-pw svg { width: 15px; height: 15px; }

    /* ── Actions ── */
    .actions {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 4px;
    }

    .btn {
      width: 100%;
      padding: 11px;
      background: var(--yellow-bright);
      border: none;
      color: #111;
      border-radius: 8px;
      cursor: pointer;
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.15rem;
      letter-spacing: 2px;
      transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
      box-shadow: 0 4px 20px rgba(200,155,0,0.3);
    }
    .btn:hover { background: var(--yellow-hover); box-shadow: 0 6px 28px rgba(200,155,0,0.4); }
    .btn:active { transform: scale(0.98); }

    .register-row {
      text-align: center;
      font-size: 0.83rem;
      color: var(--muted);
    }
    .register-row a {
      color: var(--yellow);
      text-decoration: none;
      font-weight: 600;
      transition: opacity 0.2s;
    }
    .register-row a:hover { opacity: 0.8; text-decoration: underline; }

    /* ── Responsive ── */
    @media (max-width: 480px) {
      body { padding: 16px; align-items: flex-start; padding-top: 40px; }

      .card {
        padding: 32px 22px 28px;
        border-radius: 12px;
      }

      h2 { font-size: 2rem; letter-spacing: 3px; }
    }

    @media (max-width: 360px) {
      h2 { font-size: 1.8rem; }
      input { font-size: 0.88rem; }
    }
  </style>
</head>
<body>

  <?php if (isset($_SESSION["error"])): ?>
    <div class="alert-error">
      <?php echo htmlspecialchars($_SESSION["error"]); unset($_SESSION["error"]); ?>
    </div>
  <?php endif; ?>

  <div class="card">
    <form action="controllers/AuthController.php" method="POST" autocomplete="on">
      <input type="hidden" name="action" value="login">

      <h2>Login</h2>
      <p class="subtitle">Welcome back — sign in to continue</p>

      <!-- Email -->
      <div class="field-group">
        <label for="email">Email</label>
        <div class="input-wrap">
          <i data-lucide="mail"></i>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="you@example.com"
            autocomplete="email"
            required>
        </div>
      </div>

      <!-- Password -->
      <div class="field-group">
        <label for="password">Password</label>
        <div class="input-wrap">
          <i data-lucide="lock"></i>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required>
          <button type="button" class="toggle-pw" id="togglePw" aria-label="Show password">
            <i data-lucide="eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <!-- Actions -->
      <div class="actions">
        <button class="btn" type="submit">Login</button>
        <p class="register-row">Don't have an account? <a href="registration.php">Register</a></p>
      </div>

    </form>
  </div>

  <script>
    lucide.createIcons();

    // Password visibility toggle
    const togglePw  = document.getElementById('togglePw');
    const pwInput   = document.getElementById('password');
    const eyeIcon   = document.getElementById('eyeIcon');
    let   visible   = false;

    togglePw.addEventListener('click', () => {
      visible = !visible;
      pwInput.type = visible ? 'text' : 'password';
      eyeIcon.setAttribute('data-lucide', visible ? 'eye-off' : 'eye');
      lucide.createIcons();
    });

    // Auto-dismiss error alert
    const alert = document.querySelector('.alert-error');
    if (alert) setTimeout(() => alert.style.display = 'none', 4000);
  </script>
</body>
</html>