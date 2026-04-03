<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Gym Sidebar</title>

    <style>
        body {
            margin: 0;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0d6efd;
            color: white;
            position: fixed;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px;
        }

        .sidebar a:hover {
            background: #084298;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center py-3">GYM SYSTEM</h4>

    <a href="">🏠 Dashboard</a>
    <a href="member.php">👤 Members</a>
    <a href="membership.php">💳 Membership</a>
    <a href="attendance.php">📅 Attendance</a>
    <a href="classes">📊 Classes</a>
    <a href="enrollments">⚙️ Enrollments</a>
    <a href="login.php">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2>MEMBER</h2>
    <p>Select an option from the sidebar.</p>
</div>

</body>
</html>