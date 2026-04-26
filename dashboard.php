<?php require_once("./controllers/AdminController/get-dashboard.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
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
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            transition: 0.3s;
        }
        .sidebar a svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar a:hover { background: #ffd700; color: #000; padding-left: 25px; }
        .sidebar a.active { background: #ffd700; color: #000; }

        /* ── Content ── */
        .content { margin-left: 250px; padding: 30px; }
        .content h2 { color: #ffd700; font-weight: 600; margin-bottom: 4px; }
        .content > p { color: #666; font-size: 13px; margin-bottom: 24px; }

        /* ── Stat Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
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

        .stat-label {
            font-size: 11px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }
        .stat-value { font-size: 26px; font-weight: 600; color: #fff; }
        .stat-sub   { font-size: 11px; color: #555; margin-top: 4px; }
        .stat-sub .up   { color: #22c55e; }
        .stat-sub .down { color: #ef4444; }
        .stat-icon { position: absolute; right: 14px; top: 16px; opacity: .1; }
        .stat-icon i { width: 38px; height: 38px; stroke: #ffd700; }

        /* ── Inner cards ── */
        .card {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            padding: 20px;
            border-radius: 10px;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .card-title { font-size: 14px; font-weight: 600; color: #fff; }
        .badge-pill { font-size: 11px; padding: 3px 10px; border-radius: 20px; }
        .badge-gold  { background: #2a2200; color: #ffd700; border: 1px solid #ffd70033; }
        .badge-green { background: #052010; color: #22c55e; border: 1px solid #22c55e33; }
        .badge-blue  { background: #051020; color: #3b82f6; border: 1px solid #3b82f633; }

        /* ── Grid layouts ── */
        .grid-3 { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* ── Chart ── */
        .chart-wrap { position: relative; width: 100%; height: 185px; }

        /* ── Plan list ── */
        .plan-list { display: flex; flex-direction: column; gap: 12px; }
        .plan-row  { display: flex; align-items: center; gap: 10px; }
        .plan-color { width: 4px; height: 36px; border-radius: 4px; flex-shrink: 0; }
        .plan-info  { flex: 1; }
        .plan-name  { font-size: 13px; color: #ddd; font-weight: 500; }
        .plan-count { font-size: 11px; color: #555; }
        .plan-bar-wrap { width: 80px; }
        .plan-bar      { height: 4px; border-radius: 2px; background: #2a2a2a; }
        .plan-bar-fill { height: 100%; border-radius: 2px; }
        .plan-revenue  { font-size: 12px; color: #888; min-width: 64px; text-align: right; }

        /* ── Classes ── */
        .class-list { display: flex; flex-direction: column; gap: 8px; }
        .class-row  {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            background: #141414;
            border-radius: 8px;
            border: 1px solid #222;
        }
        .class-time     { font-size: 11px; color: #ffd700; min-width: 52px; font-weight: 600; }
        .class-info     { flex: 1; }
        .class-name     { font-size: 13px; color: #ddd; font-weight: 500; }
        .class-trainer  { font-size: 11px; color: #555; }
        .class-enrolled { font-size: 11px; color: #22c55e; }
        .no-classes     { font-size: 13px; color: #555; text-align: center; padding: 20px 0; }

        /* ── Members ── */
        .member-list { display: flex; flex-direction: column; gap: 10px; }
        .member-row  { display: flex; align-items: center; gap: 12px; }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #2a2a2a;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 600; color: #ffd700; flex-shrink: 0;
        }
        .member-info  { flex: 1; }
        .member-name  { font-size: 13px; color: #ddd; font-weight: 500; }
        .member-meta  { font-size: 11px; color: #555; }
        .mem-badge    { font-size: 10px; padding: 2px 8px; border-radius: 12px; white-space: nowrap; }
        .mem-vip      { background: #2a2200; color: #ffd700; }
        .mem-premium  { background: #2a2200; color: #ffd700; }
        .mem-standard { background: #0f1f0f; color: #22c55e; }
        .mem-basic    { background: #0f1525; color: #3b82f6; }
        .mem-default  { background: #1f1f1f; color: #888; }

        /* ── Enrollments table (matches your table style) ── */
        .table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
            border: 1px solid #ffd700;
            margin-bottom: 0;
        }
        .table thead { background: #ffd700; color: #000; }
        .table thead th {
            font-weight: 600;
            padding: 8px 10px;
            font-size: 12px;
            border-right: 1px solid #000;
            border-bottom: none;
        }
        .table thead th:last-child { border-right: none; }
        .table tbody tr { border-bottom: 1px solid #ffd700; background: #000 !important; }
        .table tbody td {
            padding: 8px 10px;
            font-size: 12px;
            vertical-align: middle;
            border-right: 1px solid #ffd700;
            color: #fff;
            background: #000 !important;
        }
        .table tbody td:last-child { border-right: none; }
        .table tbody tr:hover td   { background: #1a1a1a !important; }

        .status-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; margin-right: 5px; }
        .dot-green  { background: #22c55e; }
        .dot-yellow { background: #f59e0b; }
        .dot-red    { background: #ef4444; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="dashboard.php" class="active"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="member.php"><i data-lucide="users"></i> Members</a>
    <a href="membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollments.php"><i data-lucide="settings"></i> Enrollments</a>
    <a href="./controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2>DASHBOARD</h2>
    <p>Welcome back — <?= date('l, F j, Y') ?></p>

    <!-- Stat Cards -->
    <div class="stats-grid">
        <div class="stat-card gold">
            <div class="stat-label">Total Members</div>
            <div class="stat-value"><?= $total_members ?></div>
            <div class="stat-sub">
                <span class="up">+<?= $new_members_this_month ?></span> new this month
            </div>
            <div class="stat-icon"><i data-lucide="users"></i></div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Active Memberships</div>
            <div class="stat-value"><?= $active_memberships ?></div>
            <div class="stat-sub">
                <?php $rate = $total_members > 0 ? round(($active_memberships / $total_members) * 100) : 0; ?>
                <span class="up"><?= $rate ?>%</span> active rate
            </div>
            <div class="stat-icon"><i data-lucide="credit-card"></i></div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Today's Attendance</div>
            <div class="stat-value"><?= $today_attendance ?></div>
            <div class="stat-sub">check-ins today</div>
            <div class="stat-icon"><i data-lucide="calendar-check"></i></div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value"><?= peso($monthly_revenue) ?></div>
            <div class="stat-sub">
                <?php if ($revenue_change !== null): ?>
                    <span class="<?= $revenue_change >= 0 ? 'up' : 'down' ?>">
                        <?= ($revenue_change >= 0 ? '+' : '') . $revenue_change ?>%
                    </span> vs last month
                <?php else: ?>
                    no data last month
                <?php endif; ?>
            </div>
            <div class="stat-icon"><i data-lucide="banknote"></i></div>
        </div>
    </div>

    <!-- Weekly Attendance Chart + Membership Plans -->
    <div class="grid-3">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Weekly Attendance</span>
                <span class="badge-pill badge-blue">This week</span>
            </div>
            <div class="chart-wrap">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Membership Plans</span>
                <span class="badge-pill badge-gold">Revenue</span>
            </div>
            <div class="plan-list">
                <?php if (empty($membership_plans)): ?>
                    <p style="font-size:13px;color:#555;">No membership data.</p>
                <?php else: ?>
                    <?php foreach ($membership_plans as $i => $plan): ?>
                        <?php
                            $color = $plan_colors[$i % count($plan_colors)];
                            $pct   = $max_plan_count > 0 ? round(($plan['cnt'] / $max_plan_count) * 100) : 0;
                        ?>
                        <div class="plan-row">
                            <div class="plan-color" style="background:<?= $color ?>"></div>
                            <div class="plan-info">
                                <div class="plan-name"><?= htmlspecialchars($plan['type']) ?></div>
                                <div class="plan-count"><?= $plan['cnt'] ?> member<?= $plan['cnt'] != 1 ? 's' : '' ?></div>
                            </div>
                            <div class="plan-bar-wrap">
                                <div class="plan-bar">
                                    <div class="plan-bar-fill" style="width:<?= $pct ?>%;background:<?= $color ?>"></div>
                                </div>
                            </div>
                            <div class="plan-revenue"><?= peso($plan['revenue']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Today's Classes + Recent Members & Enrollments -->
    <div class="grid-2" style="margin-top:16px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Today's Classes</span>
                <span class="badge-pill badge-green"><?= count($todays_classes) ?> scheduled</span>
            </div>
            <?php if (empty($todays_classes)): ?>
                <p class="no-classes">No classes scheduled for today.</p>
            <?php else: ?>
                <div class="class-list">
                    <?php foreach ($todays_classes as $class): ?>
                        <div class="class-row">
                            <div class="class-time"><?= date('g:i A', strtotime($class['time'])) ?></div>
                            <div class="class-info">
                                <div class="class-name"><?= htmlspecialchars($class['name']) ?></div>
                                <div class="class-trainer"><?= htmlspecialchars($class['instructor']) ?></div>
                            </div>
                            <div class="class-enrolled"><?= $class['enrolled'] ?> enrolled</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card" style="display:flex;flex-direction:column;gap:20px;">

            <!-- Recent Members -->
            <div>
                <div class="card-header" style="margin-bottom:12px;">
                    <span class="card-title">Recent Members</span>
                    <span class="badge-pill badge-gold">Latest</span>
                </div>
                <div class="member-list">
                    <?php if (empty($recent_members)): ?>
                        <p style="font-size:13px;color:#555;">No members yet.</p>
                    <?php else: ?>
                        <?php foreach ($recent_members as $m): ?>
                            <?php [$badgeClass, $badgeLabel] = member_badge($m['membership_type']); ?>
                            <div class="member-row">
                                <div class="avatar"><?= initials($m['first_name'], $m['last_name']) ?></div>
                                <div class="member-info">
                                    <div class="member-name"><?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?></div>
                                    <div class="member-meta"><?= ucfirst($m['status']) ?></div>
                                </div>
                                <?php if ($m['membership_type']): ?>
                                    <span class="mem-badge <?= $badgeClass ?>"><?= htmlspecialchars($badgeLabel) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Enrollments -->
            <div>
                <div class="card-header" style="margin-bottom:10px;">
                    <span class="card-title">Recent Enrollments</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Class</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_enrollments)): ?>
                                <tr>
                                    <td colspan="3" style="text-align:center;color:#555;padding:16px;">No enrollments yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_enrollments as $e): ?>
                                    <?php
                                        $dotClass = 'dot-yellow';
                                        if ($e['status'] === 'approved') $dotClass = 'dot-green';
                                        if ($e['status'] === 'rejected') $dotClass = 'dot-red';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($e['member_name']) ?></td>
                                        <td><?= htmlspecialchars($e['class_name']) ?></td>
                                        <td>
                                            <span class="status-dot <?= $dotClass ?>"></span>
                                            <?= ucfirst($e['status']) ?>
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
</div>

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
                y: { ticks: { color: '#555', font: { size: 11 } }, grid: { color: '#1f1f1f' }, beginAtZero: true }
            }
        }
    });
</script>
</body>
</html>