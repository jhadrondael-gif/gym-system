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

        .table tbody td:last-child {
            border-right: none;
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

        .badge-inactive {
            background: #dc3545;
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
    <a href="#"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="member.php" class="active"><i data-lucide="users"></i> Members</a>
    <a href="membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollment.php"><i data-lucide="settings"></i> Enrollments</a>
    <a href="login.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2>MEMBER</h2>
    <p>Select an option from the sidebar.</p>

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>Maria Santos</td>
                        <td>Female</td>
                        <td>1996-03-12</td>
                        <td>09171112222</td>
                        <td>maria@email.com</td>
                        <td><span class="badge-active">Active</span></td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Jose Reyes</td>
                        <td>Male</td>
                        <td>1989-07-25</td>
                        <td>09183334444</td>
                        <td>jose@email.com</td>
                        <td><span class="badge-active">Active</span></td>
                    </tr>
                    <tr>
                        <td>003</td>
                        <td>Ana Cruz</td>
                        <td>Female</td>
                        <td>2001-11-04</td>
                        <td>09195556666</td>
                        <td>ana@email.com</td>
                        <td><span class="badge-inactive">Inactive</span></td>
                    </tr>
                    <tr>
                        <td>004</td>
                        <td>Carlo Lim</td>
                        <td>Male</td>
                        <td>1993-05-18</td>
                        <td>09201234567</td>
                        <td>carlo@email.com</td>
                        <td><span class="badge-active">Active</span></td>
                    </tr>
                    <tr>
                        <td>005</td>
                        <td>Nina Gomez</td>
                        <td>Female</td>
                        <td>1998-09-30</td>
                        <td>09221234567</td>
                        <td>nina@email.com</td>
                        <td><span class="badge-inactive">Inactive</span></td>
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