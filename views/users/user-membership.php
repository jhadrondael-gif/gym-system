<?php require_once("../../controllers/UserController/get-user-membership.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Membership – Gym System</title>
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
            z-index: 1040;
            transition: transform 0.3s ease;
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

        /* ── Mobile top bar ── */
        .topbar {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 56px;
            background: #0a0a0a;
            border-bottom: 2px solid #ffd700;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 1035;
        }
        .topbar-title {
            color: #ffd700;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 1px;
        }
        .topbar-toggle {
            background: none;
            border: none;
            color: #ffd700;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 4px;
        }
        .topbar-toggle svg { width: 22px; height: 22px; }

        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 1039;
        }
        .sidebar-overlay.active { display: block; }

        /* ── Content ── */
        .content { margin-left: 250px; padding: 30px 30px 48px; }
        .content h2 { color: #ffd700; font-weight: 600; margin-bottom: 4px; }
        .subtitle { color: #666; font-size: 13px; margin-bottom: 24px; }

        /* ── Alerts ── */
        .alert { font-size: 13px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #052010; border: 1px solid #22c55e44; color: #22c55e; }
        .alert-danger  { background: #2a0a0a; border: 1px solid #ef444444; color: #ef4444; }
        .alert .btn-close { filter: invert(1); opacity: .6; }

        /* ── Section header ── */
        .section-header {
            display: flex; align-items: center; gap: 12px;
            margin: 28px 0 14px;
        }
        .section-header .section-label {
            font-size: 12px; font-weight: 600;
            color: #ffd700; text-transform: uppercase; letter-spacing: 1px;
            white-space: nowrap;
        }
        .section-header .section-line { flex: 1; height: 1px; background: #222; }

        /* ── Active membership banner ── */
        .active-banner {
            background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
            border: 1px solid #22c55e44;
            border-radius: 12px;
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .active-banner-left { display: flex; flex-direction: column; gap: 6px; }
        .active-plan-type { font-size: 26px; font-weight: 600; color: #ffd700; }
        .active-plan-meta { font-size: 13px; color: #666; display: flex; gap: 16px; flex-wrap: wrap; }
        .active-plan-meta span { display: flex; align-items: center; gap: 5px; }
        .active-plan-meta svg { width: 13px; height: 13px; color: #ffd700; }

        .active-banner-right { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
        .days-badge {
            font-size: 13px; font-weight: 600;
            padding: 6px 14px; border-radius: 20px;
        }
        .days-ok      { background: #052010; color: #22c55e; border: 1px solid #22c55e44; }
        .days-warn    { background: #2a1f00; color: #f59e0b; border: 1px solid #f59e0b44; }
        .days-danger  { background: #2a0a0a; color: #ef4444; border: 1px solid #ef444433; }

        .days-bar-wrap { width: 180px; }
        .days-bar { height: 6px; background: #2a2a2a; border-radius: 4px; overflow: hidden; }
        .days-bar-fill { height: 100%; border-radius: 4px; }

        .btn-cancel-mem {
            background: transparent;
            color: #ef4444;
            border: 1px solid #ef4444;
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 4px;
        }
        .btn-cancel-mem svg { width: 14px; height: 14px; }
        .btn-cancel-mem:hover { background: #ef4444; color: #fff; }

        /* ── Pending banner ── */
        .pending-banner {
            background: #1a1500;
            border: 1px solid #f59e0b44;
            border-radius: 12px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .pending-banner-left { display: flex; align-items: center; gap: 12px; }
        .pending-icon { color: #f59e0b; }
        .pending-icon svg { width: 24px; height: 24px; }
        .pending-text .pending-title { font-size: 14px; font-weight: 600; color: #f59e0b; }
        .pending-text .pending-sub   { font-size: 12px; color: #666; margin-top: 2px; }
        .btn-withdraw {
            background: transparent;
            color: #f59e0b;
            border: 1px solid #f59e0b44;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-withdraw svg { width: 13px; height: 13px; }
        .btn-withdraw:hover { background: #f59e0b; color: #000; }

        /* ── Plan cards ── */
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }
        .plan-card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 24px 22px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: border-color 0.2s;
            position: relative;
            overflow: hidden;
        }
        .plan-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .plan-card.plan-basic::before   { background: #3b82f6; }
        .plan-card.plan-premium::before { background: #ffd700; }
        .plan-card.plan-vip::before     { background: #a855f7; }

        .plan-card:hover { border-color: #ffd70044; }
        .plan-card.current-plan { border-color: #22c55e55; }

        .plan-card-header { display: flex; align-items: flex-start; justify-content: space-between; }
        .plan-name { font-size: 18px; font-weight: 600; }
        .plan-basic   .plan-name { color: #3b82f6; }
        .plan-premium .plan-name { color: #ffd700; }
        .plan-vip     .plan-name { color: #a855f7; }

        .plan-current-badge {
            font-size: 10px; padding: 3px 8px; border-radius: 12px;
            background: #052010; color: #22c55e; border: 1px solid #22c55e44;
        }

        .plan-price {
            font-size: 28px; font-weight: 600; color: #fff;
        }
        .plan-price span { font-size: 13px; color: #555; font-weight: 400; }

        .plan-features { display: flex; flex-direction: column; gap: 8px; }
        .plan-feature {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #888;
        }
        .plan-feature svg { width: 14px; height: 14px; color: #22c55e; flex-shrink: 0; }

        .btn-request {
            background: #ffd700;
            color: #000;
            font-weight: 600;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: 0.2s;
            width: 100%;
            margin-top: auto;
        }
        .btn-request svg { width: 14px; height: 14px; }
        .btn-request:hover { background: #e6c200; }
        .btn-request:disabled {
            background: #2a2a2a; color: #555; cursor: not-allowed; border: 1px solid #333;
        }

        /* ── History table ── */
        .table {
            width: 100%; border-collapse: collapse;
            color: #fff; border: 1px solid #ffd700; margin-bottom: 0;
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

        .badge-active    { background: #052010; color: #22c55e; border: 1px solid #22c55e44; padding: 3px 10px; border-radius: 20px; font-size: 11px; }
        .badge-expired   { background: #2a0a0a; color: #ef4444; border: 1px solid #ef444433; padding: 3px 10px; border-radius: 20px; font-size: 11px; }
        .badge-cancelled { background: #1f1f1f; color: #888;    border: 1px solid #33333344; padding: 3px 10px; border-radius: 20px; font-size: 11px; }
        .badge-pending   { background: #2a1f00; color: #f59e0b; border: 1px solid #f59e0b44; padding: 3px 10px; border-radius: 20px; font-size: 11px; }

        /* ── Modals ── */
        .modal-content {
            background: #1a1a1a; border: 1px solid #ffd700;
            color: #fff; border-radius: 10px;
        }
        .modal-header { border-bottom: 1px solid #2a2a2a; padding: 16px 20px; }
        .modal-footer { border-top: 1px solid #2a2a2a; padding: 14px 20px; }
        .modal-title  { color: #ffd700; font-weight: 600; font-size: 15px; }
        .modal-body   { padding: 20px; font-size: 13px; color: #ccc; }
        .btn-close    { filter: invert(1); opacity: .6; }

        .modal-plan-name { color: #ffd700; font-weight: 600; }

        .form-label { color: #aaa; font-size: 12px; margin-bottom: 4px; display: block; }
        .form-control, .form-select {
            background: #111; border: 1px solid #333; color: #fff;
            border-radius: 6px; font-size: 13px; padding: 8px 12px;
        }
        .form-control:focus, .form-select:focus {
            background: #111; border-color: #ffd700; color: #fff;
            box-shadow: 0 0 0 3px rgba(255,215,0,.15);
        }
        .form-control[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }

        .btn-gold {
            background: #ffd700; color: #000; font-weight: 600;
            border: none; padding: 8px 18px; border-radius: 8px; font-size: 13px; cursor: pointer;
        }
        .btn-gold:hover { background: #e6c200; color: #000; }
        .btn-secondary {
            background: #2a2a2a; border: 1px solid #444; color: #ccc;
            font-size: 13px; border-radius: 6px; padding: 7px 16px;
        }
        .btn-secondary:hover { background: #333; color: #fff; }
        .btn-danger {
            background: #ef4444; border: none; color: #fff;
            font-size: 13px; font-weight: 600; border-radius: 6px; padding: 7px 16px;
        }
        .btn-danger:hover { background: #dc2626; color: #fff; }

        /* No active state */
        .no-membership {
            background: #1a1a1a; border: 1px dashed #2a2a2a;
            border-radius: 12px; padding: 32px;
            text-align: center; color: #444;
        }
        .no-membership svg { width: 40px; height: 40px; margin-bottom: 10px; opacity: .3; }
        .no-membership p { font-size: 13px; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .topbar { display: flex; }

            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: 80px 16px 48px;
            }

            .active-banner {
                flex-direction: column;
                align-items: flex-start;
                padding: 18px 16px;
                gap: 14px;
            }
            .active-banner-right {
                align-items: flex-start;
                width: 100%;
            }
            .days-bar-wrap { width: 100%; }

            .active-plan-type { font-size: 22px; }
            .active-plan-meta { flex-direction: column; gap: 6px; }

            .pending-banner {
                flex-direction: column;
                align-items: flex-start;
                padding: 14px 16px;
                gap: 12px;
            }

            .plans-grid {
                grid-template-columns: 1fr;
            }

            .table thead th,
            .table tbody td {
                font-size: 11px;
                padding: 6px 8px;
            }

            /* Stack history table on very small screens */
            .table-stack thead { display: none; }
            .table-stack tbody tr {
                display: block;
                border: 1px solid #ffd70033;
                border-radius: 8px;
                margin-bottom: 10px;
                padding: 10px 12px;
                background: #0a0a0a !important;
            }
            .table-stack tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border: none !important;
                padding: 5px 0;
                font-size: 12px;
                background: transparent !important;
            }
            .table-stack tbody td::before {
                content: attr(data-label);
                color: #555;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 10px;
                letter-spacing: 0.5px;
                flex-shrink: 0;
                margin-right: 8px;
            }
            .table-stack tbody tr:hover td { background: transparent !important; }
            .table-stack .empty-row {
                display: table-row;
            }
            .table-stack .empty-row td {
                display: table-cell;
            }

            .modal-dialog { margin: 16px; }
        }

        @media (max-width: 400px) {
            .content h2 { font-size: 20px; }
            .plan-price { font-size: 24px; }
        }
    </style>
</head>
<body>

<!-- Mobile Top Bar -->
<div class="topbar">
    <span class="topbar-title">GYM SYSTEM</span>
    <button class="topbar-toggle" id="sidebarToggle" aria-label="Open menu">
        <i data-lucide="menu"></i>
    </button>
</div>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="user-dashboard.php"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="user-membership.php" class="active"><i data-lucide="credit-card"></i> Membership</a>
    <a href="user-attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="user-classes.php"><i data-lucide="dumbbell"></i> Classes</a>
    <div class="spacer"></div>
    <a href="../../controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2>MEMBERSHIP</h2>
    <p class="subtitle">View your plan, request a new membership, or manage your current one.</p>

    <?php if (!empty($_SESSION["success"])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION["success"]) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION["error"])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION["error"]) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <!-- ── Current Membership ── -->
    <div class="section-header">
        <span class="section-label">Current Membership</span>
        <div class="section-line"></div>
    </div>

    <?php if ($pending_membership): ?>
        <div class="pending-banner" style="margin-bottom:14px;">
            <div class="pending-banner-left">
                <div class="pending-icon"><i data-lucide="clock"></i></div>
                <div class="pending-text">
                    <div class="pending-title">Pending Request — <?= htmlspecialchars($pending_membership['type']) ?></div>
                    <div class="pending-sub">Submitted on <?= date('M d, Y', strtotime($pending_membership['start_date'])) ?> · Awaiting admin approval</div>
                </div>
            </div>
            <button class="btn-withdraw"
                data-bs-toggle="modal"
                data-bs-target="#cancelModal"
                data-id="<?= $pending_membership['id'] ?>"
                data-type="<?= htmlspecialchars($pending_membership['type']) ?>"
                data-context="pending">
                <i data-lucide="x"></i> Withdraw Request
            </button>
        </div>
    <?php endif; ?>

    <?php if ($active_membership): ?>
        <?php
            $dl = days_left($active_membership['end_date']);
            $total_days = max((strtotime($active_membership['end_date']) - strtotime($active_membership['start_date'])) / 86400, 1);
            $used_days  = max((time() - strtotime($active_membership['start_date'])) / 86400, 0);
            $pct_used   = min(round(($used_days / $total_days) * 100), 100);
            $remaining_pct = 100 - $pct_used;
            $bar_color  = $dl <= 7 ? '#ef4444' : ($dl <= 14 ? '#f59e0b' : '#22c55e');
            $badge_class = $dl <= 7 ? 'days-danger' : ($dl <= 14 ? 'days-warn' : 'days-ok');
        ?>
        <div class="active-banner">
            <div class="active-banner-left">
                <div class="active-plan-type"><?= htmlspecialchars($active_membership['type']) ?></div>
                <div class="active-plan-meta">
                    <span><i data-lucide="calendar"></i> <?= date('M d, Y', strtotime($active_membership['start_date'])) ?> – <?= date('M d, Y', strtotime($active_membership['end_date'])) ?></span>
                    <span><i data-lucide="banknote"></i> ₱<?= number_format((float)$active_membership['fee'], 0) ?></span>
                </div>
            </div>
            <div class="active-banner-right">
                <span class="days-badge <?= $badge_class ?>"><?= $dl ?> day<?= $dl != 1 ? 's' : '' ?> left</span>
                <div class="days-bar-wrap">
                    <div class="days-bar">
                        <div class="days-bar-fill" style="width:<?= $remaining_pct ?>%;background:<?= $bar_color ?>;"></div>
                    </div>
                </div>
                <?php if (!$pending_membership): ?>
                    <button class="btn-cancel-mem"
                        data-bs-toggle="modal"
                        data-bs-target="#cancelModal"
                        data-id="<?= $active_membership['id'] ?>"
                        data-type="<?= htmlspecialchars($active_membership['type']) ?>"
                        data-context="active">
                        <i data-lucide="x-circle"></i> Cancel Membership
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <?php if (!$pending_membership): ?>
            <div class="no-membership">
                <i data-lucide="credit-card"></i>
                <p>You have no active membership.<br>Choose a plan below to get started.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- ── Available Plans ── -->
    <div class="section-header">
        <span class="section-label">Available Plans</span>
        <div class="section-line"></div>
    </div>

    <?php
    $plans = [
        [
            'type'     => 'Basic',
            'price'    => 500,
            'duration' => '1 month',
            'class'    => 'plan-basic',
            'features' => ['Gym access (6AM – 9PM)', 'Locker use', 'Free fitness assessment'],
        ],
        [
            'type'     => 'Premium',
            'price'    => 1200,
            'duration' => '1 month',
            'class'    => 'plan-premium',
            'features' => ['All Basic features', 'Unlimited group classes', 'Guest pass (1/month)', 'Priority locker'],
        ],
        [
            'type'     => 'VIP',
            'price'    => 2000,
            'duration' => '1 month',
            'class'    => 'plan-vip',
            'features' => ['All Premium features', 'Personal trainer (4 sessions)', '24/7 gym access', 'Nutrition consultation'],
        ],
    ];

    $current_type  = $active_membership  ? strtolower($active_membership['type'])  : null;
    $pending_type  = $pending_membership ? strtolower($pending_membership['type'])  : null;
    $has_active    = !is_null($active_membership);
    $has_pending   = !is_null($pending_membership);
    ?>

    <div class="plans-grid">
        <?php foreach ($plans as $plan): ?>
            <?php
                $is_current = $current_type === strtolower($plan['type']);
                $is_pending = $pending_type === strtolower($plan['type']);
                $is_disabled = $has_active || $has_pending;
            ?>
            <div class="plan-card <?= $plan['class'] ?> <?= $is_current ? 'current-plan' : '' ?>">
                <div class="plan-card-header">
                    <div class="plan-name"><?= $plan['type'] ?></div>
                    <?php if ($is_current): ?>
                        <span class="plan-current-badge">Current</span>
                    <?php elseif ($is_pending): ?>
                        <span class="plan-current-badge" style="background:#2a1f00;color:#f59e0b;border-color:#f59e0b44;">Pending</span>
                    <?php endif; ?>
                </div>

                <div class="plan-price">
                    ₱<?= number_format($plan['price'], 0) ?>
                    <span>/ <?= $plan['duration'] ?></span>
                </div>

                <div class="plan-features">
                    <?php foreach ($plan['features'] as $feat): ?>
                        <div class="plan-feature">
                            <i data-lucide="check"></i>
                            <?= htmlspecialchars($feat) ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($is_current): ?>
                    <button class="btn-request" disabled>
                        <i data-lucide="check-circle"></i> Current Plan
                    </button>
                <?php elseif ($is_pending): ?>
                    <button class="btn-request" disabled>
                        <i data-lucide="clock"></i> Request Pending
                    </button>
                <?php elseif ($is_disabled): ?>
                    <button class="btn-request" disabled>
                        <i data-lucide="lock"></i> Cancel Current Plan First
                    </button>
                <?php else: ?>
                    <button class="btn-request"
                        data-bs-toggle="modal"
                        data-bs-target="#requestModal"
                        data-type="<?= $plan['type'] ?>"
                        data-price="<?= $plan['price'] ?>">
                        <i data-lucide="plus-circle"></i> Request This Plan
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ── Membership History ── -->
    <div class="section-header">
        <span class="section-label">Membership History</span>
        <div class="section-line"></div>
    </div>

    <div class="table-responsive">
        <table class="table table-stack">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Fee</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($memberships)): ?>
                    <tr class="empty-row"><td colspan="5">No membership history yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($memberships as $ms): ?>
                        <tr>
                            <td data-label="Type"><?= htmlspecialchars($ms['type']) ?></td>
                            <td data-label="Start"><?= date('M d, Y', strtotime($ms['start_date'])) ?></td>
                            <td data-label="End"><?= date('M d, Y', strtotime($ms['end_date'])) ?></td>
                            <td data-label="Fee">₱<?= number_format((float)$ms['fee'], 0) ?></td>
                            <td data-label="Status">
                                <?php $s = strtolower($ms['status']); ?>
                                <?php if ($s === 'active'): ?>
                                    <span class="badge-active">Active</span>
                                <?php elseif ($s === 'expired'): ?>
                                    <span class="badge-expired">Expired</span>
                                <?php elseif ($s === 'pending'): ?>
                                    <span class="badge-pending">Pending</span>
                                <?php else: ?>
                                    <span class="badge-cancelled">Cancelled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Request Plan Modal -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Membership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../controllers/UserController/request-membership.php" method="POST">
                <input type="hidden" name="type"  id="req_type">
                <input type="hidden" name="fee"   id="req_fee">
                <div class="modal-body">
                    <p style="margin-bottom:14px;">
                        You are requesting the
                        <strong class="modal-plan-name" id="req_plan_name"></strong> plan.
                        An admin will review and approve your request.
                    </p>
                    <div style="margin-bottom:10px;">
                        <label class="form-label">Preferred Start Date</label>
                        <input type="date" name="start_date" id="req_start_date" class="form-control" required
                            min="<?= date('Y-m-d') ?>">
                    </div>
                    <div>
                        <label class="form-label">Preferred End Date</label>
                        <input type="date" name="end_date" id="req_end_date" class="form-control" required
                            min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel / Withdraw Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancel_modal_title">Cancel Membership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../controllers/UserController/cancel-membership.php" method="POST">
                <input type="hidden" name="id" id="cancel_id">
                <div class="modal-body">
                    <p id="cancel_modal_body">
                        Are you sure you want to cancel your
                        <strong class="modal-plan-name" id="cancel_plan_name"></strong> membership?
                        This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep It</button>
                    <button type="submit" class="btn btn-danger" id="cancel_submit_btn">Yes, Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();

    // ── Mobile sidebar toggle ──
    const sidebar        = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarToggle  = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.add('open');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    sidebarToggle.addEventListener('click', openSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    // Close sidebar when a nav link is tapped on mobile
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) closeSidebar();
        });
    });

    // Request modal
    document.getElementById('requestModal').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('req_type').value            = btn.dataset.type;
        document.getElementById('req_fee').value             = btn.dataset.price;
        document.getElementById('req_plan_name').textContent = btn.dataset.type;

        // Default start = today, end = +1 month
        const today = new Date();
        const end   = new Date();
        end.setMonth(end.getMonth() + 1);
        document.getElementById('req_start_date').value = today.toISOString().split('T')[0];
        document.getElementById('req_end_date').value   = end.toISOString().split('T')[0];
    });

    // Cancel / Withdraw modal
    document.getElementById('cancelModal').addEventListener('show.bs.modal', function(e) {
        const btn     = e.relatedTarget;
        const context = btn.dataset.context;

        document.getElementById('cancel_id').value             = btn.dataset.id;
        document.getElementById('cancel_plan_name').textContent = btn.dataset.type;

        if (context === 'pending') {
            document.getElementById('cancel_modal_title').textContent = 'Withdraw Request';
            document.getElementById('cancel_submit_btn').textContent  = 'Yes, Withdraw';
            document.getElementById('cancel_modal_body').innerHTML    =
                'Are you sure you want to withdraw your <strong style="color:#ffd700">' + btn.dataset.type + '</strong> membership request?';
        } else {
            document.getElementById('cancel_modal_title').textContent = 'Cancel Membership';
            document.getElementById('cancel_submit_btn').textContent  = 'Yes, Cancel';
            document.getElementById('cancel_modal_body').innerHTML    =
                'Are you sure you want to cancel your <strong style="color:#ffd700">' + btn.dataset.type + '</strong> membership? This action cannot be undone.';
        }
    });
</script>
</body>
</html>