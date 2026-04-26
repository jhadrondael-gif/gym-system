<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$database = new Database();
$conn     = $database->connection();

$stmt = $conn->prepare("
    SELECT
        c.id,
        c.name,
        c.instructor,
        c.day,
        c.time,
        c.description,
        (SELECT COUNT(*) FROM enrollments e2
         WHERE e2.class_id = c.id AND e2.status = 'approved') AS enrolled_count,
        e.id     AS enrollment_id,
        e.status AS enrollment_status
    FROM classes c
    LEFT JOIN enrollments e
           ON e.class_id  = c.id
          AND e.member_id = ?
          AND e.status   != 'rejected'
          AND e.status   != 'cancelled'
    ORDER BY c.name ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Classes – Gym System</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #111;
            color: #fff;
        }

        /* ─────────────────────────────────────────
           SIDEBAR
        ───────────────────────────────────────── */
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
            font-size: 14px;
        }
        .sidebar a svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar a:hover  { background: #ffd700; color: #000; padding-left: 25px; }
        .sidebar a.active { background: #ffd700; color: #000; }
        .sidebar .spacer  { flex: 1; }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.7);
            }
        }

        /* ── Overlay ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1039;
        }
        .sidebar-overlay.show { display: block; }

        /* ─────────────────────────────────────────
           TOPBAR (mobile only)
        ───────────────────────────────────────── */
        .topbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 1030;
            background: #0a0a0a;
            border-bottom: 2px solid #ffd700;
            padding: 12px 16px;
            align-items: center;
            justify-content: space-between;
        }
        .topbar-title { color: #ffd700; font-weight: 600; font-size: 16px; }
        .burger {
            background: none; border: none;
            color: #ffd700; cursor: pointer;
            padding: 4px; display: flex; align-items: center;
        }
        .burger svg { width: 22px; height: 22px; }

        @media (max-width: 991px) { .topbar { display: flex; } }

        /* ─────────────────────────────────────────
           CONTENT
        ───────────────────────────────────────── */
        .content { margin-left: 250px; padding: 30px 30px 48px; }
        .content h2 { color: #ffd700; font-weight: 600; margin-bottom: 4px; }
        .subtitle { color: #666; font-size: 13px; margin-bottom: 24px; }

        @media (max-width: 991px) {
            .content { margin-left: 0; padding: 20px 16px 48px; }
        }

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
        .section-label {
            font-size: 12px; font-weight: 600;
            color: #ffd700; text-transform: uppercase; letter-spacing: 1px;
            white-space: nowrap;
        }
        .section-line { flex: 1; height: 1px; background: #222; }

        /* ── Classes grid ── */
        .classes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }
        @media (max-width: 575px) {
            .classes-grid { grid-template-columns: 1fr; }
        }

        /* ── Class card ── */
        .class-card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            transition: border-color 0.2s;
            position: relative;
        }
        .class-card:hover { border-color: #ffd70033; }
        .class-card.is-enrolled { border-color: #22c55e44; }
        .class-card.is-pending  { border-color: #f59e0b44; }

        .class-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 12px 12px 0 0;
            background: #2a2a2a;
        }
        .class-card.is-enrolled::before { background: #22c55e; }
        .class-card.is-pending::before  { background: #f59e0b; }

        .class-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
        }
        .class-card-name {
            font-size: 16px; font-weight: 600;
            color: #fff; line-height: 1.3;
        }

        .status-badge {
            font-size: 10px; font-weight: 600;
            padding: 3px 9px; border-radius: 12px;
            white-space: nowrap; flex-shrink: 0;
        }
        .badge-enrolled { background: #052010; color: #22c55e; border: 1px solid #22c55e44; }
        .badge-pending  { background: #2a1f00; color: #f59e0b; border: 1px solid #f59e0b44; }

        .class-meta { display: flex; flex-direction: column; gap: 5px; font-size: 12px; color: #666; }
        .class-meta-row { display: flex; align-items: center; gap: 6px; }
        .class-meta-row svg { width: 13px; height: 13px; color: #ffd700; flex-shrink: 0; }

        .class-description { font-size: 12px; color: #555; line-height: 1.6; }

        .class-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            gap: 8px;
            flex-wrap: wrap;
        }
        .enrolled-count {
            font-size: 12px; color: #555;
            display: flex; align-items: center; gap: 5px;
        }
        .enrolled-count svg { width: 13px; height: 13px; }

        /* ── Action buttons ── */
        .btn-enroll {
            background: #ffd700; color: #000; font-weight: 600;
            border: none; padding: 8px 16px; border-radius: 8px;
            font-size: 12px; display: inline-flex; align-items: center;
            gap: 5px; cursor: pointer; transition: 0.2s;
        }
        .btn-enroll svg { width: 13px; height: 13px; }
        .btn-enroll:hover { background: #e6c200; }

        .btn-withdraw {
            background: transparent; color: #f59e0b;
            border: 1px solid #f59e0b55; padding: 8px 14px;
            border-radius: 8px; font-size: 12px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 5px;
            cursor: pointer; transition: 0.2s;
        }
        .btn-withdraw svg { width: 13px; height: 13px; }
        .btn-withdraw:hover { background: #f59e0b; color: #000; }

        .btn-unenroll {
            background: transparent; color: #ef4444;
            border: 1px solid #ef444444; padding: 8px 14px;
            border-radius: 8px; font-size: 12px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 5px;
            cursor: pointer; transition: 0.2s;
        }
        .btn-unenroll svg { width: 13px; height: 13px; }
        .btn-unenroll:hover { background: #ef4444; color: #fff; }

        /* ── Empty state ── */
        .no-classes {
            background: #1a1a1a; border: 1px dashed #2a2a2a;
            border-radius: 12px; padding: 40px;
            text-align: center; color: #444;
        }
        .no-classes svg { width: 36px; height: 36px; margin-bottom: 10px; opacity: .3; }
        .no-classes p { font-size: 13px; }

        /* ── Modals ── */
        .modal-content {
            background: #1a1a1a; border: 1px solid #ffd700;
            color: #fff; border-radius: 10px;
        }
        .modal-header { border-bottom: 1px solid #2a2a2a; padding: 16px 20px; }
        .modal-footer { border-top:   1px solid #2a2a2a; padding: 14px 20px; }
        .modal-title  { color: #ffd700; font-weight: 600; font-size: 15px; }
        .modal-body   { padding: 20px; font-size: 13px; color: #ccc; }
        .btn-close    { filter: invert(1); opacity: .6; }
        .modal-class-name { color: #ffd700; font-weight: 600; }

        .btn-gold {
            background: #ffd700; color: #000; font-weight: 600;
            border: none; padding: 8px 18px; border-radius: 8px;
            font-size: 13px; cursor: pointer;
        }
        .btn-gold:hover { background: #e6c200; color: #000; }
        .btn-secondary-modal {
            background: #2a2a2a; border: 1px solid #444; color: #ccc;
            font-size: 13px; border-radius: 6px; padding: 7px 16px;
        }
        .btn-secondary-modal:hover { background: #333; color: #fff; }
        .btn-danger-modal {
            background: #ef4444; border: none; color: #fff;
            font-size: 13px; font-weight: 600; border-radius: 6px; padding: 7px 16px;
        }
        .btn-danger-modal:hover { background: #dc2626; }
    </style>
</head>
<body>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Topbar (mobile) -->
<div class="topbar">
    <span class="topbar-title">GYM SYSTEM</span>
    <button class="burger" onclick="openSidebar()" aria-label="Open menu">
        <i data-lucide="menu"></i>
    </button>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="user-dashboard.php"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="user-membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="user-attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="user-classes.php" class="active"><i data-lucide="dumbbell"></i> Classes</a>
    <div class="spacer"></div>
    <a href="../../controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2>CLASSES</h2>
    <p class="subtitle">Browse available classes and manage your enrollments.</p>

    <?php if (!empty($_SESSION["success"])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION["success"] ?>
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

    <?php
        $my_classes = array_filter($classes, fn($c) => !empty($c['enrollment_status']));
        $available  = array_filter($classes, fn($c) =>  empty($c['enrollment_status']));
    ?>

    <!-- My Enrollments -->
    <?php if (!empty($my_classes)): ?>
        <div class="section-header">
            <span class="section-label">My Enrollments</span>
            <div class="section-line"></div>
        </div>
        <div class="classes-grid">
            <?php foreach ($my_classes as $class): ?>
                <?php
                    $status    = strtolower($class['enrollment_status']);
                    $cardClass = $status === 'approved' ? 'is-enrolled' : 'is-pending';
                ?>
                <div class="class-card <?= $cardClass ?>">
                    <div class="class-card-header">
                        <div class="class-card-name"><?= htmlspecialchars($class['name']) ?></div>
                        <?php if ($status === 'approved'): ?>
                            <span class="status-badge badge-enrolled">Enrolled</span>
                        <?php else: ?>
                            <span class="status-badge badge-pending">Pending</span>
                        <?php endif; ?>
                    </div>

                    <div class="class-meta">
                        <div class="class-meta-row">
                            <i data-lucide="user"></i>
                            <?= htmlspecialchars($class['instructor']) ?>
                        </div>
                        <div class="class-meta-row">
                            <i data-lucide="calendar"></i>
                            <?= htmlspecialchars($class['day']) ?>
                        </div>
                        <div class="class-meta-row">
                            <i data-lucide="clock"></i>
                            <?= htmlspecialchars(date('g:i A', strtotime($class['time']))) ?>
                        </div>
                    </div>

                    <?php if (!empty($class['description'])): ?>
                        <div class="class-description">
                            <?= htmlspecialchars(mb_strimwidth($class['description'], 0, 100, '...')) ?>
                        </div>
                    <?php endif; ?>

                    <div class="class-footer">
                        <div class="enrolled-count">
                            <i data-lucide="users"></i>
                            <?= $class['enrolled_count'] ?> enrolled
                        </div>
                        <?php if ($status === 'pending'): ?>
                            <button class="btn-withdraw"
                                data-bs-toggle="modal"
                                data-bs-target="#cancelModal"
                                data-enrollment-id="<?= $class['enrollment_id'] ?>"
                                data-class-name="<?= htmlspecialchars($class['name']) ?>"
                                data-context="pending">
                                <i data-lucide="x"></i> Withdraw
                            </button>
                        <?php else: ?>
                            <button class="btn-unenroll"
                                data-bs-toggle="modal"
                                data-bs-target="#cancelModal"
                                data-enrollment-id="<?= $class['enrollment_id'] ?>"
                                data-class-name="<?= htmlspecialchars($class['name']) ?>"
                                data-context="active">
                                <i data-lucide="x-circle"></i> Unenroll
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Available Classes -->
    <div class="section-header">
        <span class="section-label">Available Classes</span>
        <div class="section-line"></div>
    </div>

    <?php if (empty($available)): ?>
        <div class="no-classes">
            <i data-lucide="dumbbell"></i>
            <p>No other classes available right now.</p>
        </div>
    <?php else: ?>
        <div class="classes-grid">
            <?php foreach ($available as $class): ?>
                <div class="class-card">
                    <div class="class-card-header">
                        <div class="class-card-name"><?= htmlspecialchars($class['name']) ?></div>
                    </div>

                    <div class="class-meta">
                        <div class="class-meta-row">
                            <i data-lucide="user"></i>
                            <?= htmlspecialchars($class['instructor']) ?>
                        </div>
                        <div class="class-meta-row">
                            <i data-lucide="calendar"></i>
                            <?= htmlspecialchars($class['day']) ?>
                        </div>
                        <div class="class-meta-row">
                            <i data-lucide="clock"></i>
                            <?= htmlspecialchars(date('g:i A', strtotime($class['time']))) ?>
                        </div>
                    </div>

                    <?php if (!empty($class['description'])): ?>
                        <div class="class-description">
                            <?= htmlspecialchars(mb_strimwidth($class['description'], 0, 100, '...')) ?>
                        </div>
                    <?php endif; ?>

                    <div class="class-footer">
                        <div class="enrolled-count">
                            <i data-lucide="users"></i>
                            <?= $class['enrolled_count'] ?> enrolled
                        </div>
                        <button class="btn-enroll"
                            data-bs-toggle="modal"
                            data-bs-target="#enrollModal"
                            data-class-id="<?= $class['id'] ?>"
                            data-class-name="<?= htmlspecialchars($class['name']) ?>">
                            <i data-lucide="plus-circle"></i> Enroll
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Enroll Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enroll in Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../controllers/UserController/enroll-class.php" method="POST">
                <input type="hidden" name="class_id" id="enroll_class_id">
                <div class="modal-body">
                    <p>You are requesting to enroll in
                        <strong class="modal-class-name" id="enroll_class_name"></strong>.
                        An admin will review and approve your request.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel / Withdraw / Unenroll Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancel_modal_title">Cancel Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../controllers/UserController/cancel-enrollment.php" method="POST">
                <input type="hidden" name="enrollment_id" id="cancel_enrollment_id">
                <div class="modal-body">
                    <p id="cancel_modal_body"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-modal" data-bs-dismiss="modal">No, Keep It</button>
                    <button type="submit" class="btn btn-danger-modal" id="cancel_submit_btn">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();

    /* ── Sidebar toggle ── */
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    /* ── Enroll modal ── */
    document.getElementById("enrollModal").addEventListener("show.bs.modal", function(e) {
        const btn = e.relatedTarget;
        document.getElementById("enroll_class_id").value         = btn.dataset.classId;
        document.getElementById("enroll_class_name").textContent = btn.dataset.className;
    });

    /* ── Cancel / Withdraw / Unenroll modal ── */
    document.getElementById("cancelModal").addEventListener("show.bs.modal", function(e) {
        const btn     = e.relatedTarget;
        const context = btn.dataset.context;
        const name    = btn.dataset.className;

        document.getElementById("cancel_enrollment_id").value = btn.dataset.enrollmentId;

        if (context === "pending") {
            document.getElementById("cancel_modal_title").textContent = "Withdraw Request";
            document.getElementById("cancel_submit_btn").textContent  = "Yes, Withdraw";
            document.getElementById("cancel_modal_body").innerHTML    =
                `Are you sure you want to withdraw your enrollment request for <strong style="color:#ffd700">${name}</strong>?`;
        } else {
            document.getElementById("cancel_modal_title").textContent = "Unenroll from Class";
            document.getElementById("cancel_submit_btn").textContent  = "Yes, Unenroll";
            document.getElementById("cancel_modal_body").innerHTML    =
                `Are you sure you want to unenroll from <strong style="color:#ffd700">${name}</strong>? You will need to request again to re-join.`;
        }
    });
</script>
</body>
</html>