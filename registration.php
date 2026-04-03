<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gym Registration</title>
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
            --dark-bg: #1a1a1a;
            --card-bg: #111111;
            --input-bg: #1e1e1e;
            --border: #f5c800;
            --text: #f5c800;
            --muted: #888;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: 'Barlow', sans-serif;
            background-color: #2a1f0e;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(80, 50, 10, 0.6) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(60, 40, 5, 0.5) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.08'/%3E%3C/svg%3E");
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: var(--card-bg);
            padding: 36px 32px;
            border-radius: 14px;
            width: 420px;
            border: 1.5px solid var(--yellow);
            box-shadow:
                0 0 0 1px rgba(245, 200, 0, 0.08),
                0 8px 40px rgba(0, 0, 0, 0.6),
                0 0 60px rgba(245, 200, 0, 0.04);
            animation: fadeIn 0.4s ease;
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
            color: var(--yellow);
            margin-bottom: 24px;
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
            opacity: 0.7;
            pointer-events: none;
        }

        input, select {
            width: 100%;
            padding: 10px 12px 10px 38px;
            background: var(--input-bg);
            border: 1.5px solid rgba(245, 200, 0, 0.35);
            border-radius: 7px;
            color: #e0e0e0;
            font-family: 'Barlow', sans-serif;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;
            -webkit-appearance: none;
        }

        input::placeholder {
            color: var(--muted);
        }

        input:focus, select:focus {
            border-color: var(--yellow);
            box-shadow: 0 0 0 3px rgba(245, 200, 0, 0.12);
        }

        select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='7' viewBox='0 0 12 7'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23f5c800' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 32px;
        }

        select option {
            background: #1e1e1e;
            color: #e0e0e0;
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
            padding: 13px;
            background: var(--yellow);
            border: none;
            color: #111;
            border-radius: 7px;
            cursor: pointer;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.15rem;
            letter-spacing: 2px;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            margin-top: 6px;
            box-shadow: 0 4px 20px rgba(245, 200, 0, 0.25);
        }

        .btn:hover {
            background: var(--yellow-hover);
            box-shadow: 0 6px 28px rgba(245, 200, 0, 0.35);
        }

        .btn:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Register</h2>

        <form action="controllers/AuthController.php" method="POST">

            <input type="hidden" name="action" value="register">

            <div class="row">
                <div class="field-group">
                    <label>First Name</label>
                    <div class="input-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" name="first_name" placeholder="First name" required>
                    </div>
                </div>
                <div class="field-group">
                    <label>Last Name</label>
                    <div class="input-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" name="last_name" placeholder="Last name" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="field-group">
                    <label>Gender</label>
                    <div class="input-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
                        <select name="gender" required>
                            <option value="">Select</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                </div>
                <div class="field-group">
                    <label>Role</label>
                    <div class="input-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8a2 2 0 0 0-2 2v2h12V5a2 2 0 0 0-2-2z"/></svg>
                        <select name="role" required>
                            <option value="">Select</option>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field-group">
                <label>Date of Birth</label>
                <div class="input-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <input type="date" name="date_of_birth" required>
                </div>
            </div>

<<<<<<< HEAD
            <select name="role">
                <option value="">Select Role</option>
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>
=======
            <div class="field-group">
                <label>Contact Number</label>
                <div class="input-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.55a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <input type="text" name="contact_number" placeholder="Contact number" required>
                </div>
            </div>
>>>>>>> e9b1799d3f32f2662b1ee61a52a4adffef41e0b8

            <div class="field-group">
                <label>Email</label>
                <div class="input-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input type="email" name="email" placeholder="Email address" required>
                </div>
            </div>

<<<<<<< HEAD
            <input type="date" name="birthdate" required>

            <input type="text" name="contact_number" placeholder="Contact Number" required>

            <input type="email" name="email" placeholder="Email" required>

            <input type="password" name="password" placeholder="Password" required>

            <input type="password" name="Confirm_password" placeholder="Confirm_Password" required>
=======
            <div class="row">
                <div class="field-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                </div>
                <div class="field-group">
                    <label>Confirm</label>
                    <div class="input-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="Confirm_password" placeholder="Confirm" required>
                    </div>
                </div>
            </div>
>>>>>>> e9b1799d3f32f2662b1ee61a52a4adffef41e0b8

            <div class="register-link">
                <a href="login.php">Already have an account? Login</a>
            </div>

            <button type="submit" class="btn">Register</button>

        </form>
    </div>

</body>
</html>