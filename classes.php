<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Gym Sidebar</title>

    <style>
         body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #111;
            color: #fff;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0a0a0a;
            border-right: 2px solid #ffd700;
            position: fixed;
        }

        .sidebar h4 {
            text-align: center;
            padding: 20px;
            color: #ffd700;
            font-weight: 600;
            border-bottom: 1px solid #333;
        }

        .sidebar a {
            color: #ccc;
            text-decoration: none;
            display: block;
            padding: 14px 20px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #ffd700;
            color: #000;
            padding-left: 25px;
        }

        /* Active link (optional) */
        .sidebar a.active {
            background: #ffd700;
            color: #000;
        }

        /* Content */
        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .content h2 {
            color: #ffd700;
            font-weight: 600;
        }

        .card {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>

 <a href="*" class="active"><i data-lucide="layout-dashboard"></i> Dashboard</a>
<a href="member.php"><i data-lucide="users"></i> Members</a>
<a href="membership.php"><i data-lucide="credit-card"></i> Membership</a>
<a href="attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
<a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
<a href="enrollments.php"><i data-lucide="settings"></i> Enrollments</a>
<a href="login.php"><i data-lucide="log-out"></i> Logout</a>
</div>


<!-- Main Content -->
<div class="content">
    <h2>Classes</h2>
    <p>Select an option from the sidebar.</p>
</div>

</body>
</html>