<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register – Gym System</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --yellow: #f5c800;
      --yellow-hover: #e0b400;
      --dark-bg: #2a1f0e;
      --card-bg: #111111;
      --input-bg: #1e1e1e;
      --muted: #888;
    }

    body {
      font-family: 'Barlow', sans-serif;
      background-color: var(--dark-bg);
      background-image:
        radial-gradient(ellipse at 20% 50%, rgba(80,50,10,0.6) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(60,40,5,0.5)  0%, transparent 50%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.08'/%3E%3C/svg%3E");
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 24px 16px;
    }

    /* ── Error / success alert ── */
    .alert {
      position: fixed;
      top: 16px;
      left: 50%;
      transform: translateX(-50%);
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 13px;
      z-index: 999;
      max-width: calc(100vw - 32px);
      text-align: center;
    }
    .alert-error   { background:#2a0a0a; border:1px solid #ef444444; color:#ef4444; }
    .alert-success { background:#052010; border:1px solid #22c55e44; color:#22c55e; }

    /* ── Card ── */
    .form-container {
      background: var(--card-bg);
      padding: 36px 32px 32px;
      border-radius: 14px;
      width: 100%;
      max-width: 460px;
      border: 1.5px solid var(--yellow);
      box-shadow:
        0 0 0 1px rgba(245,200,0,0.08),
        0 8px 40px rgba(0,0,0,0.6),
        0 0 60px rgba(245,200,0,0.04);
      animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
      from { opacity:0; transform:translateY(16px); }
      to   { opacity:1; transform:translateY(0); }
    }

    h2 {
      text-align: center;
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2.2rem;
      letter-spacing: 3px;
      color: var(--yellow);
      margin-bottom: 6px;
    }

    .subtitle {
      text-align: center;
      font-size: 0.78rem;
      color: var(--muted);
      letter-spacing: 1px;
      margin-bottom: 24px;
    }

    /* ── Fields ── */
    form { display: flex; flex-direction: column; gap: 12px; }

    .field-group { display: flex; flex-direction: column; gap: 5px; }

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
      opacity: 0.65;
      pointer-events: none;
      flex-shrink: 0;
    }

    input, select {
      width: 100%;
      padding: 11px 12px 11px 38px;
      background: var(--input-bg);
      border: 1.5px solid rgba(245,200,0,0.3);
      border-radius: 8px;
      color: #e0e0e0;
      font-family: 'Barlow', sans-serif;
      font-size: 0.92rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      appearance: none;
      -webkit-appearance: none;
    }

    input::placeholder { color: var(--muted); }
    input:focus, select:focus {
      border-color: var(--yellow);
      box-shadow: 0 0 0 3px rgba(245,200,0,0.12);
    }

    input[type="date"] { color: #e0e0e0; }
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(0.6) sepia(1) saturate(3) hue-rotate(5deg); cursor: pointer; }

    select {
      cursor: pointer;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='7' viewBox='0 0 12 7'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23f5c800' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      padding-right: 32px;
    }
    select option { background: #1e1e1e; color: #e0e0e0; }

    /* Password toggle */
    .toggle-pw {
      position: absolute;
      right: 10px;
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

    /* Two-column row */
    .row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    /* ── Footer ── */
    .footer-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 4px;
    }

    .footer-row a {
      color: var(--yellow);
      font-size: 0.82rem;
      text-decoration: none;
      opacity: 0.85;
      transition: opacity 0.2s;
    }
    .footer-row a:hover { opacity: 1; text-decoration: underline; }

    .btn {
      flex: 1;
      min-width: 140px;
      padding: 12px;
      background: var(--yellow);
      border: none;
      color: #111;
      border-radius: 8px;
      cursor: pointer;
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.1rem;
      letter-spacing: 2px;
      transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
      box-shadow: 0 4px 20px rgba(245,200,0,0.25);
    }
    .btn:hover  { background: var(--yellow-hover); box-shadow: 0 6px 28px rgba(245,200,0,0.35); }
    .btn:active { transform: scale(0.98); }

    /* ── Responsive ── */
    @media (max-width: 480px) {
      body { padding: 20px 12px 32px; align-items: flex-start; }

      .form-container { padding: 28px 18px 24px; border-radius: 12px; }

      h2 { font-size: 1.9rem; }

      /* Stack two-column rows on small screens */
      .row { grid-template-columns: 1fr; }

      .footer-row { flex-direction: column-reverse; align-items: stretch; }
      .footer-row a { text-align: center; }
      .btn { min-width: unset; width: 100%; }
    }

    @media (max-width: 360px) {
      h2 { font-size: 1.7rem; }
      input, select { font-size: 0.88rem; }
    }
  </style>
</head>
<body>

  <?php if (!empty($_SESSION["error"])): ?>
    <div class="alert alert-error">
      <?php echo htmlspecialchars($_SESSION["error"]); unset($_SESSION["error"]); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($_SESSION["success"])): ?>
    <div class="alert alert-success">
      <?php echo htmlspecialchars($_SESSION["success"]); unset($_SESSION["success"]); ?>
    </div>
  <?php endif; ?>

  <div class="form-container">
    <h2>Register</h2>
    <p class="subtitle">Create your gym account</p>

    <form action="controllers/AuthController.php" method="POST" autocomplete="on">
      <input type="hidden" name="action" value="register">

      <!-- First / Last name -->
      <div class="row">
        <div class="field-group">
          <label for="first_name">First Name</label>
          <div class="input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" id="first_name" name="first_name" placeholder="First name" autocomplete="given-name" required>
          </div>
        </div>
        <div class="field-group">
          <label for="last_name">Last Name</label>
          <div class="input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" id="last_name" name="last_name" placeholder="Last name" autocomplete="family-name" required>
          </div>
        </div>
      </div>

      <!-- Gender / Role -->
      <div class="row">
        <div class="field-group">
          <label for="gender">Gender</label>
          <div class="input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
            <select id="gender" name="gender" required>
              <option value="">Select</option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </div>
        </div>
        <div class="field-group">
          <label for="role">Role</label>
          <div class="input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8a2 2 0 0 0-2 2v2h12V5a2 2 0 0 0-2-2z"/></svg>
            <select id="role" name="role" required>
              <option value="">Select</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Date of Birth -->
      <div class="field-group">
        <label for="birthdate">Date of Birth</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <input type="date" id="birthdate" name="birthdate" autocomplete="bday" required>
        </div>
      </div>

      <!-- Contact Number -->
      <div class="field-group">
        <label for="contact_number">Contact Number</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.55a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <input type="tel" id="contact_number" name="contact_number" placeholder="+63 9XX XXX XXXX" autocomplete="tel" required>
        </div>
      </div>

      <!-- Email -->
      <div class="field-group">
        <label for="email">Email</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <input type="email" id="email" name="email" placeholder="you@example.com" autocomplete="email" required>
        </div>
      </div>

      <!-- Password / Confirm -->
      <div class="row">
        <div class="field-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" id="password" name="password" placeholder="Password" autocomplete="new-password" required>
            <button type="button" class="toggle-pw" data-target="password" aria-label="Show password">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>
        <div class="field-group">
          <label for="confirm_password">Confirm</label>
          <div class="input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" id="confirm_password" name="Confirm_password" placeholder="Confirm" autocomplete="new-password" required>
            <button type="button" class="toggle-pw" data-target="confirm_password" aria-label="Show confirm password">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Submit row -->
      <div class="footer-row">
        <a href="login.php">Already have an account? Login</a>
        <button type="submit" class="btn">Register</button>
      </div>

    </form>
  </div>

  <script>
    // Password visibility toggles
    document.querySelectorAll('.toggle-pw').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';

        // Swap eye / eye-off icon inline
        const svg = btn.querySelector('svg');
        if (isText) {
          svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        } else {
          svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        }
        btn.setAttribute('aria-label', isText ? 'Show password' : 'Hide password');
      });
    });

    // Auto-dismiss alerts
    document.querySelectorAll('.alert').forEach(el => {
      setTimeout(() => el.style.display = 'none', 4000);
    });
  </script>
</body>
</html>