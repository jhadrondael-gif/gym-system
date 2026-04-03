<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gym Sidebar</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #111;
            color: #fff;
        }

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

        .sidebar a.active {
            background: #ffd700;
            color: #000;
        }

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

        .table {
            color: #fff;
            border: 1px solid #ffd700;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .table thead {
            background: #ffd700;
            color: #000;
        }

        .table thead th {
            font-weight: 600;
            border-right: 1px solid #000;
            border-bottom: none;
        }

        .table thead th:last-child {
            border-right: none;
        }

        .table tbody tr {
            border-bottom: 1px solid #ffd700;
            background: #000 !important;
        }

        .table tbody td {
            vertical-align: middle;
            border-right: 1px solid #ffd700;
            color: #fff;
            background: #000 !important;
        }

        .table tbody tr:hover {
            background: #1a1a1a !important;
        }

        .table tbody tr:hover td {
            background: #1a1a1a !important;
        }

        .badge-active {
            background: #28a745;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-expired {
            background: #dc3545;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-cancelled {
            background: #6c757d;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="#" class="active"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="member.php"><i data-lucide="users"></i> Members</a>
    <a href="membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollments.php"><i data-lucide="settings"></i> Enrollments</a>
    <a href="login.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2>MEMBERSHIP</h2>
    <p>Select an option from the sidebar.</p>

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th>Fee</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>M01</td>
                        <td>Maria Santos</td>
                        <td>Premium</td>
                        <td>2025-01-01</td>
                        <td>2025-12-31</td>
                        <td><span class="badge-active">Active</span></td>
                        <td>&#8369;2,500</td>
                    </tr>
                    <tr>
                        <td>M02</td>
                        <td>Jose Reyes</td>
                        <td>Basic</td>
                        <td>2025-03-01</td>
                        <td>2025-08-31</td>
                        <td><span class="badge-active">Active</span></td>
                        <td>&#8369;1,200</td>
                    </tr>
                    <tr>
                        <td>M03</td>
                        <td>Ana Cruz</td>
                        <td>Basic</td>
                        <td>2024-06-01</td>
                        <td>2024-11-30</td>
                        <td><span class="badge-expired">Expired</span></td>
                        <td>&#8369;1,200</td>
                    </tr>
                    <tr>
                        <td>M04</td>
                        <td>Carlo Lim</td>
                        <td>VIP</td>
                        <td>2025-02-01</td>
                        <td>2026-01-31</td>
                        <td><span class="badge-active">Active</span></td>
                        <td>&#8369;5,000</td>
                    </tr>
                    <tr>
                        <td>M05</td>
                        <td>Nina Gomez</td>
                        <td>Premium</td>
                        <td>2024-09-01</td>
                        <td>2025-08-31</td>
                        <td><span class="badge-cancelled">Cancelled</span></td>
                        <td>&#8369;2,500</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>