<?php require_once("../../controllers/UserController/get-user-dashboard.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <title>Dashboard – Gym System</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #111;
            color: #fff;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0a0a0a;
            border-right: 2px solid #ffd700;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
        }
        .sidebar h4 {
            text-align: center;
            padding: 20px;
            color: #ffd700;
            font-weight: 600;
            border-bottom: 1px solid #333;
            margin: 0;
        }
        .sidebar a {
            color: #ccc;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            transition: 0.3s;
        }
        .sidebar a svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar a:hover { background: #ffd700; color: #000; padding-left: 25px; }
        .sidebar a.active { background: #ffd700; color: #000; }
        .sidebar .spacer { flex: 1; }

        /* ── Content ── */
        .content { margin-left: 250px; padding: 30px 30px 48px; }

        /* ── Welcome banner ── */
        .welcome-banner {
            background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
            border: 1px solid #ffd70033;
            border-radius: 12px;
            padding: 24px 28px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .welcome-left h2 {
            color: #ffd700;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 4px;
        }
        .welcome-left p { color: #555; font-size: 13px; }
        .welcome-avatar {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: #2a2a2a;
            border: 2px solid #ffd70055;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 600; color: #ffd700;
            flex-shrink: 0;
        }

        /* ── Stat Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }
        .stat-card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            padding: 18px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .stat-card.gold::before  { background: #ffd700; }
        .stat-card.green::before { background: #22c55e; }
        .stat-card.blue::before  { background: #3b82f6; }
        .stat-card.red::before   { background: #ef4444; }
        .stat-card.orange::before{ background: #f97316; }

        .stat-label { font-size: 11px; color: #555; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
        .stat-value { font-size: 26px; font-weight: 600; color: #fff; }
        .stat-sub   { font-size: 11px; color: #555; margin-top: 4px; }
        .stat-sub .up   { color: #22c55e; }
        .stat-sub .warn { color: #f59e0b; }
        .stat-sub .danger { color: #ef4444; }
        .stat-icon { position: absolute; right: 14px; top: 16px; opacity: .1; }
        .stat-icon svg { width: 38px; height: 38px; stroke: #ffd700; }

        /* ── Cards ── */
        .card {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            padding: 20px;
            border-radius: 10px;
        }
        .card-header {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .card-title { font-size: 14px; font-weight: 600; color: #fff; }
        .badge-pill { font-size: 11px; padding: 3px 10px; border-radius: 20px; }
        .badge-gold   { background: #2a2200; color: #ffd700; border: 1px solid #ffd70033; }
        .badge-green  { background: #052010; color: #22c55e; border: 1px solid #22c55e33; }
        .badge-blue   { background: #051020; color: #3b82f6; border: 1px solid #3b82f633; }
        .badge-yellow { background: #2a1f00; color: #f59e0b; border: 1px solid #f59e0b33; }
        .badge-red    { background: #2a0a0a; color: #ef4444; border: 1px solid #ef444433; }

        /* ── Grid layouts ── */
        .grid-2   { display: grid; grid-template-columns: 1fr 1fr;   gap: 16px; margin-bottom: 16px; }
        .grid-3   { display: grid; grid-template-columns: 2fr 1fr;   gap: 16px; margin-bottom: 16px; }

        /* ── Chart ── */
        .chart-wrap { position: relative; width: 100%; height: 180px; }

        /* ── Membership card ── */
        .membership-detail {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .mem-type {
            font-size: 22px;
            font-weight: 600;
            color: #ffd700;
        }
        .mem-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            color: #888;
            padding: 8px 0;
            border-bottom: 1px solid #222;
        }
        .mem-row:last-child { border-bottom: none; }
        .mem-row .mem-val { color: #ddd; font-weight: 500; }
        .days-left-bar {
            height: 6px;
            background: #2a2a2a;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 4px;
        }
        .days-left-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.4s;
        }
        .no-membership {
            text-align: center;
            padding: 20px 0;
            color: #444;
            font-size: 13px;
        }
        .no-membership svg { width: 32px; height: 32px; margin-bottom: 8px; opacity: .3; display: block; margin: 0 auto 8px; }

        /* ── Enrolled classes ── */
        .class-list { display: flex; flex-direction: column; gap: 8px; }
        .class-row {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            background: #141414;
            border-radius: 8px;
            border: 1px solid #222;
        }
        .class-row.enrolled-row { border-color: #22c55e22; }
        .class-row.pending-row  { border-color: #f59e0b22; }
        .class-time  { font-size: 11px; color: #ffd700; min-width: 52px; font-weight: 600; }
        .class-info  { flex: 1; }
        .class-name  { font-size: 13px; color: #ddd; font-weight: 500; }
        .class-meta-text { font-size: 11px; color: #555; }
        .enroll-pill { font-size: 10px; padding: 2px 8px; border-radius: 12px; white-space: nowrap; }
        .pill-approved { background: #052010; color: #22c55e; }
        .pill-pending  { background: #2a1f00; color: #f59e0b; }
        .no-classes { text-align: center; padding: 20px 0; color: #444; font-size: 13px; }

        /* ── Attendance table ── */
        .table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
            border: 1px solid #ffd700;
            margin-bottom: 0;
        }
        .table thead { background: #ffd700; color: #000; }
        .table thead th {
            font-weight: 600; padding: 8px 10px;
            font-size: 12px; border-right: 1px solid #000; border-bottom: none;
        }
        .table thead th:last-child { border-right: none; }
        .table tbody tr { border-bottom: 1px solid #ffd700; background: #000 !important; }
        .table tbody td {
            padding: 8px 10px; font-size: 12px; vertical-align: middle;
            border-right: 1px solid #ffd700; color: #fff; background: #000 !important;
        }
        .table tbody td:last-child { border-right: none; }
        .table tbody tr:hover td { background: #1a1a1a !important; }
        .empty-row td { text-align: center; color: #555; padding: 20px; }

        .badge-in  { background: #052010; color: #22c55e; border: 1px solid #22c55e44; padding: 3px 10px; border-radius: 20px; font-size: 11px; }
        .badge-out { background: #1f1f1f; color: #888;    border: 1px solid #33333344; padding: 3px 10px; border-radius: 20px; font-size: 11px; }
        .duration-text { font-size: 11px; color: #aaa; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="user-dashboard.php" class="active"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="user-membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="user-attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="user-classes.php"><i data-lucide="dumbbell"></i> Classes</a>
    <div class="spacer"></div>
    <a href="../../controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-left">
            <h2>Welcome back, <?= htmlspecialchars($member['first_name']) ?>!</h2>
            <p><?= date('l, F j, Y') ?></p>
        </div>
        <div class="welcome-avatar">
            <?= strtoupper(substr($member['first_name'],0,1) . substr($member['last_name'],0,1)) ?>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="stats-grid">
        <!-- Membership status -->
        <div class="stat-card gold">
            <div class="stat-label">Membership</div>
            <div class="stat-value"><?= $membership ? htmlspecialchars($membership['type']) : '—' ?></div>
            <div class="stat-sub">
                <?php if ($membership): ?>
                    <span class="up">Active</span> plan
                <?php else: ?>
                    <span class="danger">No active plan</span>
                <?php endif; ?>
            </div>
            <div class="stat-icon"><i data-lucide="credit-card"></i></div>
        </div>

        <!-- Days left -->
        <div class="stat-card <?= ($days_left !== null && $days_left <= 7) ? 'red' : 'green' ?>">
            <div class="stat-label">Days Left</div>
            <div class="stat-value"><?= $days_left !== null ? $days_left : '—' ?></div>
            <div class="stat-sub">
                <?php if ($days_left !== null): ?>
                    <?php if ($days_left <= 7): ?>
                        <span class="danger">Expiring soon</span>
                    <?php else: ?>
                        until expiry
                    <?php endif; ?>
                <?php else: ?>
                    no active membership
                <?php endif; ?>
            </div>
            <div class="stat-icon"><i data-lucide="calendar"></i></div>
        </div>

        <!-- Total check-ins -->
        <div class="stat-card blue">
            <div class="stat-label">Total Check-ins</div>
            <div class="stat-value"><?= $total_checkins ?></div>
            <div class="stat-sub">all time</div>
            <div class="stat-icon"><i data-lucide="calendar-check"></i></div>
        </div>

        <!-- This month -->
        <div class="stat-card orange">
            <div class="stat-label">This Month</div>
            <div class="stat-value"><?= $checkins_this_month ?></div>
            <div class="stat-sub">check-ins in <?= date('F') ?></div>
            <div class="stat-icon"><i data-lucide="flame"></i></div>
        </div>
    </div>

    <!-- Weekly Chart + Membership Detail -->
    <div class="grid-3">
        <div class="card">
            <div class="card-header">
                <span class="card-title">My Attendance This Week</span>
                <span class="badge-pill badge-blue">This week</span>
            </div>
            <div class="chart-wrap">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">My Membership</span>
                <?php if ($membership): ?>
                    <span class="badge-pill badge-green">Active</span>
                <?php else: ?>
                    <span class="badge-pill badge-red">None</span>
                <?php endif; ?>
            </div>
            <?php if ($membership): ?>
                <?php
                    $total_days = max((strtotime($membership['end_date']) - strtotime($membership['start_date'])) / 86400, 1);
                    $used_days  = max((time() - strtotime($membership['start_date'])) / 86400, 0);
                    $pct        = min(round(($used_days / $total_days) * 100), 100);
                    $bar_color  = $days_left <= 7 ? '#ef4444' : ($days_left <= 14 ? '#f59e0b' : '#22c55e');
                ?>
                <div class="membership-detail">
                    <div class="mem-type"><?= htmlspecialchars($membership['type']) ?></div>
                    <div class="mem-row">
                        <span>Start Date</span>
                        <span class="mem-val"><?= date('M d, Y', strtotime($membership['start_date'])) ?></span>
                    </div>
                    <div class="mem-row">
                        <span>End Date</span>
                        <span class="mem-val"><?= date('M d, Y', strtotime($membership['end_date'])) ?></span>
                    </div>
                    <div class="mem-row">
                        <span>Fee</span>
                        <span class="mem-val">₱<?= number_format((float)$membership['fee'], 0) ?></span>
                    </div>
                    <div class="mem-row" style="border:none;flex-direction:column;align-items:flex-start;gap:6px;">
                        <span><?= $days_left ?> day<?= $days_left != 1 ? 's' : '' ?> remaining</span>
                        <div class="days-left-bar" style="width:100%;">
                            <div class="days-left-fill" style="width:<?= 100 - $pct ?>%;background:<?= $bar_color ?>;"></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-membership">
                    <i data-lucide="credit-card"></i>
                    <p>You have no active membership.<br>Contact the gym to get started.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Enrolled Classes + Recent Attendance -->
    <div class="grid-2">
        <div class="card">
            <div class="card-header">
                <span class="card-title">My Classes</span>
                <span class="badge-pill badge-gold"><?= count($enrolled_classes) ?> enrolled</span>
            </div>
            <?php if (empty($enrolled_classes)): ?>
                <p class="no-classes">You are not enrolled in any classes yet.<br>
                    <a href="user-classes.php" style="color:#ffd700;font-size:12px;">Browse classes →</a>
                </p>
            <?php else: ?>
                <div class="class-list">
                    <?php foreach ($enrolled_classes as $ec): ?>
                        <?php
                            $row_class = $ec['enrollment_status'] === 'approved' ? 'enrolled-row' : 'pending-row';
                            $pill_class = $ec['enrollment_status'] === 'approved' ? 'pill-approved' : 'pill-pending';
                            $pill_label = $ec['enrollment_status'] === 'approved' ? 'Enrolled' : 'Pending';
                        ?>
                        <div class="class-row <?= $row_class ?>">
                            <div class="class-time"><?= date('g:i A', strtotime($ec['time'])) ?></div>
                            <div class="class-info">
                                <div class="class-name"><?= htmlspecialchars($ec['name']) ?></div>
                                <div class="class-meta-text"><?= htmlspecialchars($ec['day']) ?> · <?= htmlspecialchars($ec['instructor']) ?></div>
                            </div>
                            <span class="enroll-pill <?= $pill_class ?>"><?= $pill_label ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Recent Attendance</span>
                <span class="badge-pill badge-blue">Last 7 logs</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check-In</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_attendance)): ?>
                            <tr class="empty-row"><td colspan="4">No attendance records yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recent_attendance as $log): ?>
                                <?php
                                    $dur = duration($log['check_in'], $log['check_out']);
                                    $is_in = !$log['check_out'];
                                ?>
                                <tr>
                                    <td><?= date('M d', strtotime($log['check_in'])) ?></td>
                                    <td><?= date('g:i A', strtotime($log['check_in'])) ?></td>
                                    <td>
                                        <?php if ($dur): ?>
                                            <span class="duration-text"><?= $dur ?></span>
                                        <?php else: ?>
                                            <span style="color:#555;font-size:11px;">In progress</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($is_in): ?>
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
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();

    new Chart(document.getElementById('attendanceChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_map(fn($d) => substr($d, 0, 3), array_keys($weekly))) ?>,
            datasets: [{
                label: 'Check-ins',
                data: <?= json_encode(array_values($weekly)) ?>,
                backgroundColor: '#ffd70033',
                borderColor: '#ffd700',
                borderWidth: 1.5,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#555', font: { size: 11 } }, grid: { color: '#1f1f1f' } },
                y: {
                    ticks: { color: '#555', font: { size: 11 }, stepSize: 1 },
                    grid: { color: '#1f1f1f' },
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>