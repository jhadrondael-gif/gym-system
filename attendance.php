<?php
require_once("./controllers/AdminController/get-attendance.php");
$attendance = $response["data"] ?? [];
?>

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

        .empty-row td {
            text-align: center;
            color: #888;
            padding: 30px;
        }

        .badge-in {
            background: #28a745;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-out {
            background: #6c757d;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .filter-bar {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .filter-bar input[type="date"],
        .filter-bar input[type="text"] {
            background: #111;
            border: 1px solid #444;
            color: #fff;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 14px;
        }

        .filter-bar input[type="date"]:focus,
        .filter-bar input[type="text"]:focus {
            outline: none;
            border-color: #ffd700;
        }

        .filter-bar input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        .btn-gold {
            background: #ffd700;
            color: #000;
            font-weight: 600;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-gold:hover {
            background: #e6c200;
        }

        .duration-text {
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>
<<<<<<< HEAD

    <a href="#" class="active"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="member.php"><i data-lucide="users"></i> Members</a>
    <a href="membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollments.php"><i data-lucide="settings"></i> Enrollments</a>
    <a href="login.php"><i data-lucide="log-out"></i> Logout</a>
=======
    <a href="dashboard.php"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="member.php"><i data-lucide="users"></i> Members</a>
    <a href="membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="attendance.php" class="active"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollments.php"><i data-lucide="settings"></i> Enrollments</a>
    <a href="./controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
>>>>>>> cb2fcb3e9e720e9cb5b5fcf94bd090df8257168c
</div>

<!-- Main Content -->
<div class="content">
<<<<<<< HEAD
    <h2>ATTENDANCE</h2>
   
=======

    <h2>ATTENDANCE LOGS</h2>
    <p>Read-only log of all member check-ins and check-outs.</p>

    <div class="card">

        <!-- Filter Bar -->
        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="Search member..." oninput="filterTable()">
            <input type="date" id="dateFilter" onchange="filterTable()">
            <button class="btn-gold" onclick="clearFilters()">Clear</button>
            <span id="recordCount" style="color:#aaa;font-size:13px;margin-left:auto;"></span>
        </div>

        <div class="table-responsive">
            <table class="table" id="attendanceTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Member</th>
                        <th>Date</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($attendance)): ?>
                        <tr class="empty-row">
                            <td colspan="7">No attendance records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($attendance as $i => $log): ?>
                            <?php
                                $checkIn  = $log["check_in"];
                                $checkOut = $log["check_out"];
                                $date     = date("M d, Y", strtotime($checkIn));
                                $inTime   = date("h:i A", strtotime($checkIn));
                                $outTime  = $checkOut ? date("h:i A", strtotime($checkOut)) : null;

                                // Compute duration
                                $duration = "—";
                                if ($checkOut) {
                                    $diff    = strtotime($checkOut) - strtotime($checkIn);
                                    $hours   = floor($diff / 3600);
                                    $minutes = floor(($diff % 3600) / 60);
                                    $duration = $hours > 0
                                        ? "{$hours}h {$minutes}m"
                                        : "{$minutes}m";
                                }

                                $isCheckedIn = !$checkOut;
                                $rawDate = date("Y-m-d", strtotime($checkIn));
                            ?>
                            <tr data-member="<?= strtolower(htmlspecialchars($log['member_name'])) ?>"
                                data-date="<?= $rawDate ?>">
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($log["member_name"]) ?></td>
                                <td><?= $date ?></td>
                                <td><?= $inTime ?></td>
                                <td><?= $outTime ?? '<span style="color:#888;">—</span>' ?></td>
                                <td>
                                    <?php if ($checkOut): ?>
                                        <span class="duration-text"><?= $duration ?></span>
                                    <?php else: ?>
                                        <span style="color:#888;font-size:12px;">In progress</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($isCheckedIn): ?>
                                        <span class="badge-in">Checked In</span>
                                    <?php else: ?>
                                        <span class="badge-out">Checked Out</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
>>>>>>> cb2fcb3e9e720e9cb5b5fcf94bd090df8257168c
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();

    function filterTable() {
        const search = document.getElementById("searchInput").value.toLowerCase();
        const date   = document.getElementById("dateFilter").value;
        const rows   = document.querySelectorAll("#tableBody tr[data-member]");
        let visible  = 0;

        rows.forEach(row => {
            const memberMatch = row.dataset.member.includes(search);
            const dateMatch   = !date || row.dataset.date === date;
            const show        = memberMatch && dateMatch;
            row.style.display = show ? "" : "none";
            if (show) visible++;
        });

        document.getElementById("recordCount").textContent =
            visible + " record" + (visible !== 1 ? "s" : "") + " shown";
    }

    function clearFilters() {
        document.getElementById("searchInput").value = "";
        document.getElementById("dateFilter").value  = "";
        filterTable();
    }

    // Init count
    filterTable();
</script>
</body>
</html>