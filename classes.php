<?php
require_once("./controllers/AdminController/get-classes.php");
$classes = $response["data"] ?? [];

$success = $_SESSION["success"] ?? null;
$error   = $_SESSION["error"]   ?? null;
unset($_SESSION["success"], $_SESSION["error"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Classes</title>
    <style>
        body {
            margin: 0;
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

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .content h2 {
            color: #ffd700;
            font-weight: 600;
        }

        /* Layout */
        .classes-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 20px;
            margin-top: 20px;
            align-items: start;
        }

        /* Card */
        .card {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            border-radius: 10px;
            padding: 20px;
        }

        .card-title {
            color: #ffd700;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Class rows */
        .class-row {
            background: #111;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .class-row:last-child { margin-bottom: 0; }
        .class-row:hover      { border-color: #ffd700; background: #1a1a0a; }
        .class-row.active     { border-color: #ffd700; background: #1a1a0a; }

        .class-name {
            font-weight: 600;
            font-size: 14px;
            color: #fff;
        }

        .class-meta {
            font-size: 12px;
            color: #888;
            margin-top: 3px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .class-meta span { display: flex; align-items: center; gap: 4px; }
        .class-meta svg  { width: 12px; height: 12px; }

        .enrolled-badge {
            background: #ffd700;
            color: #000;
            font-weight: 700;
            font-size: 12px;
            border-radius: 20px;
            padding: 3px 10px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* pending count pill on class row */
        .pending-pill {
            background: #2a1f00;
            color: #f59e0b;
            border: 1px solid #f59e0b55;
            font-size: 11px;
            font-weight: 600;
            border-radius: 20px;
            padding: 2px 8px;
            margin-left: 6px;
            white-space: nowrap;
        }

        .empty-classes {
            text-align: center;
            color: #555;
            padding: 40px 0;
            font-size: 14px;
        }

        /* Members panel */
        .members-panel { position: sticky; top: 30px; }

        /* Tab bar */
        .panel-tabs {
            display: flex;
            border-bottom: 1px solid #2a2a2a;
            margin-bottom: 14px;
            gap: 0;
        }
        .panel-tab {
            flex: 1;
            text-align: center;
            padding: 8px 0;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: 0.2s;
            user-select: none;
        }
        .panel-tab:hover { color: #ccc; }
        .panel-tab.active-tab {
            color: #ffd700;
            border-bottom-color: #ffd700;
        }
        .panel-tab .tab-count {
            display: inline-block;
            background: #2a2a2a;
            border-radius: 10px;
            padding: 1px 6px;
            font-size: 10px;
            margin-left: 4px;
        }
        .panel-tab.active-tab .tab-count {
            background: #2a1f00;
            color: #f59e0b;
        }

        /* Member item */
        .member-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #222;
        }
        .member-item:last-child { border-bottom: none; }

        .member-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffd700;
            color: #000;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .member-info .name  { font-size: 14px; font-weight: 500; color: #fff; }
        .member-info .since { font-size: 11px; color: #666; margin-top: 1px; }

        /* Pending member item */
        .pending-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #222;
        }
        .pending-item:last-child { border-bottom: none; }

        .pending-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2a1f00;
            border: 1px solid #f59e0b55;
            color: #f59e0b;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pending-info        { flex: 1; min-width: 0; }
        .pending-info .name  { font-size: 13px; font-weight: 500; color: #fff; }
        .pending-info .since { font-size: 11px; color: #666; margin-top: 1px; }

        .pending-actions { display: flex; gap: 5px; flex-shrink: 0; }

        .btn-approve-sm {
            background: transparent;
            border: 1px solid #22c55e;
            color: #22c55e;
            padding: 3px 9px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .btn-approve-sm svg { width: 11px; height: 11px; }
        .btn-approve-sm:hover { background: #22c55e; color: #000; }

        .btn-reject-sm {
            background: transparent;
            border: 1px solid #f59e0b;
            color: #f59e0b;
            padding: 3px 9px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .btn-reject-sm svg { width: 11px; height: 11px; }
        .btn-reject-sm:hover { background: #f59e0b; color: #000; }

        /* Toast */
        .toast-bar {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #1a1a1a;
            border: 1px solid #333;
            color: #fff;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 13px;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s, transform 0.3s;
            white-space: nowrap;
            pointer-events: none;
        }
        .toast-bar.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .toast-bar.toast-success { border-color: #22c55e55; color: #22c55e; }
        .toast-bar.toast-error   { border-color: #ef444455; color: #ef4444; }

        /* Misc panel states */
        .panel-placeholder {
            text-align: center;
            color: #444;
            padding: 50px 10px;
            font-size: 13px;
            line-height: 1.8;
        }
        .panel-placeholder svg { width: 32px; height: 32px; color: #333; margin-bottom: 10px; }

        .panel-empty {
            text-align: center;
            color: #555;
            padding: 30px 0;
            font-size: 13px;
        }

        .loading-text {
            text-align: center;
            color: #555;
            padding: 40px 0;
            font-size: 13px;
        }

        /* Search */
        .panel-search {
            background: #111;
            border: 1px solid #333;
            border-radius: 6px;
            padding: 7px 12px;
            color: #fff;
            font-size: 13px;
            width: 100%;
            margin-bottom: 12px;
        }
        .panel-search:focus { outline: none; border-color: #ffd700; }

        /* Action buttons */
        .btn-gold {
            background: #ffd700;
            color: #000;
            font-weight: 600;
            border: none;
            padding: 7px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-gold:hover { background: #e6c200; }

        .btn-edit {
            background: transparent;
            border: 1px solid #ffd700;
            color: #ffd700;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-edit:hover { background: #ffd700; color: #000; }

        .btn-delete {
            background: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.2s;
            margin-left: 4px;
        }
        .btn-delete:hover { background: #dc3545; color: #fff; }

        .class-actions { display: flex; gap: 4px; margin-top: 8px; }

        /* Modal */
        .modal-content {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            color: #fff;
        }
        .modal-header { border-bottom: 1px solid #333; }
        .modal-footer { border-top:   1px solid #333; }
        .modal-title  { color: #ffd700; font-weight: 600; }
        .btn-close    { filter: invert(1); }

        .form-label { color: #ccc; font-size: 14px; }
        .form-control, .form-select {
            background: #111; border: 1px solid #444; color: #fff;
        }
        .form-control:focus, .form-select:focus {
            background: #111; border-color: #ffd700; color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255,215,0,0.2);
        }
        .form-select option { background: #1a1a1a; }
        textarea.form-control { resize: vertical; min-height: 80px; }
    </style>
</head>
<>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="dashboard.php"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="member.php"><i data-lucide="users"></i> Members</a>
    <a href="membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="classes.php" class="active"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollments.php"><i data-lucide="settings"></i> Enrollments</a>
    <a href="./controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>CLASSES</h2>
            <p style="color:#888;margin:0;">Click a class to view its enrolled members.</p>
        </div>
        <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#createClassModal">
            <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Class
        </button>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="classes-layout">

        <!-- Left: Classes list -->
        <div class="card">
            <div class="card-title">
                <i data-lucide="bar-chart-3" style="width:16px;height:16px;"></i>
                All Classes
                <span style="color:#555;font-weight:400;font-size:13px;margin-left:4px;">(<?= count($classes) ?>)</span>
            </div>

            <?php if (empty($classes)): ?>
                <div class="empty-classes">
                    <i data-lucide="inbox" style="width:28px;height:28px;color:#333;display:block;margin:0 auto 10px;"></i>
                    No classes yet. Add one to get started.
                </div>
            <?php else: ?>
                <?php foreach ($classes as $class): ?>
                    <div class="class-row"
                         id="class-row-<?= $class['id'] ?>"
                         onclick="loadMembers(<?= $class['id'] ?>, '<?= htmlspecialchars(addslashes($class['name'])) ?>', <?= $class['enrolled_count'] ?>)">
                        <div style="flex:1;min-width:0;">
                            <div class="class-name">
                                <?= htmlspecialchars($class['name']) ?>
                                <?php if (!empty($class['pending_count']) && $class['pending_count'] > 0): ?>
                                    <span class="pending-pill"><?= $class['pending_count'] ?> pending</span>
                                <?php endif; ?>
                            </div>
                            <div class="class-meta">
                                <span>
                                    <i data-lucide="user"></i>
                                    <?= htmlspecialchars($class['instructor']) ?>
                                </span>
                                <span>
                                    <i data-lucide="clock"></i>
                                    <?= htmlspecialchars($class['day']) ?> &bull; <?= htmlspecialchars($class['time']) ?>
                                </span>
                            </div>
                            <?php if (!empty($class['description'])): ?>
                                <div style="font-size:12px;color:#555;margin-top:4px;">
                                    <?= htmlspecialchars(mb_strimwidth($class['description'], 0, 80, '...')) ?>
                                </div>
                            <?php endif; ?>
                            <div class="class-actions">
                                <button class="btn-edit"
                                    onclick="event.stopPropagation();"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateClassModal"
                                    data-id="<?= $class['id'] ?>"
                                    data-name="<?= htmlspecialchars($class['name']) ?>"
                                    data-instructor="<?= htmlspecialchars($class['instructor']) ?>"
                                    data-description="<?= htmlspecialchars($class['description']) ?>"
                                    data-day="<?= htmlspecialchars($class['day']) ?>"
                                    data-time="<?= $class['time'] ?>">
                                    <i data-lucide="pencil" style="width:11px;height:11px;"></i> Edit
                                </button>
                                <button class="btn-delete"
                                    onclick="event.stopPropagation();"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteClassModal"
                                    data-id="<?= $class['id'] ?>"
                                    data-name="<?= htmlspecialchars($class['name']) ?>">
                                    <i data-lucide="trash-2" style="width:11px;height:11px;"></i> Delete
                                </button>
                            </div>
                        </div>
                        <div style="margin-left:12px;">
                            <div class="enrolled-badge"><?= $class['enrolled_count'] ?> enrolled</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Right: Members panel -->
        <div class="members-panel">
            <div class="card">
                <div class="card-title">
                    <i data-lucide="users" style="width:16px;height:16px;"></i>
                    <span id="panelTitle">Enrolled Members</span>
                </div>

                <!-- Tab bar (hidden until a class is selected) -->
                <div class="panel-tabs" id="panelTabs" style="display:none;">
                    <div class="panel-tab active-tab" id="tab-enrolled" onclick="switchTab('enrolled')">
                        Enrolled <span class="tab-count" id="count-enrolled">0</span>
                    </div>
                    <div class="panel-tab" id="tab-pending" onclick="switchTab('pending')">
                        Pending <span class="tab-count" id="count-pending">0</span>
                    </div>
                </div>

                <div id="panelContent">
                    <div class="panel-placeholder">
                        <i data-lucide="mouse-pointer-click"></i>
                        <br>Select a class on the left<br>to view its members.
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Toast notification -->
<div class="toast-bar" id="toastBar"></div>

<!-- Create Class Modal -->
<div class="modal fade" id="createClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/create-class.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Class Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Morning Yoga">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Instructor</label>
                            <input type="text" name="instructor" class="form-control" required placeholder="e.g. Juan Dela Cruz">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Day</label>
                            <select name="day" class="form-select" required>
                                <option value="" disabled selected>Select day</option>
                                <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                                <option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time</label>
                            <input type="time" name="time" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description <span style="color:#555;">(optional)</span></label>
                            <textarea name="description" class="form-control" placeholder="Brief description of the class..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Create Class</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Class Modal -->
<div class="modal fade" id="updateClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/update-class.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Class Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Instructor</label>
                            <input type="text" name="instructor" id="edit_instructor" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Day</label>
                            <select name="day" id="edit_day" class="form-select" required>
                                <option value="" disabled>Select day</option>
                                <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                                <option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time</label>
                            <input type="time" name="time" id="edit_time" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description <span style="color:#555;">(optional)</span></label>
                            <textarea name="description" id="edit_description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Class Modal -->
<div class="modal fade" id="deleteClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/delete-class.php" method="POST">
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="delete_name" style="color:#ffd700;"></strong>? All enrollments for this class will also be removed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
lucide.createIcons();

// ── State ─────────────────────────────────────────────────
let activeClassId   = null;
let activeClassName = "";
let enrolledCache   = [];
let pendingCache    = [];
let currentTab      = "enrolled";

// ── Load members for a class ─────────────────────────────
function loadMembers(classId, className, count) {
    document.querySelectorAll(".class-row").forEach(r => r.classList.remove("active"));
    document.getElementById("class-row-" + classId).classList.add("active");

    activeClassId   = classId;
    activeClassName = className;
    currentTab      = "enrolled";

    document.getElementById("panelTitle").textContent = className;
    document.getElementById("panelTabs").style.display = "flex";
    document.getElementById("panelContent").innerHTML =
        `<div class="loading-text">Loading...</div>`;

    fetch(`./controllers/AdminController/get-class-members.php?class_id=${classId}`)
        .then(r => r.json())
        .then(data => {
            const all = data.data || [];

            // ✅ KEY FIX: split by status field returned from the DB
            enrolledCache = all.filter(m => m.status === "approved");
            pendingCache  = all.filter(m => m.status === "pending");

            updateTabCounts();
            setActiveTab("enrolled");
            renderTab();
        })
        .catch(() => {
            document.getElementById("panelContent").innerHTML =
                `<div class="panel-empty">Failed to load members.</div>`;
        });
}

// ── Tab switching ─────────────────────────────────────────
function switchTab(tab) {
    currentTab = tab;
    setActiveTab(tab);
    renderTab();
}

function setActiveTab(tab) {
    document.querySelectorAll(".panel-tab").forEach(t => t.classList.remove("active-tab"));
    document.getElementById("tab-" + tab).classList.add("active-tab");
}

function updateTabCounts() {
    document.getElementById("count-enrolled").textContent = enrolledCache.length;
    document.getElementById("count-pending").textContent  = pendingCache.length;
}

// ── Render current tab ────────────────────────────────────
function renderTab() {
    if (currentTab === "enrolled") {
        renderEnrolled(enrolledCache);
    } else {
        renderPending(pendingCache);
    }
}

// ── Enrolled tab ──────────────────────────────────────────
function renderEnrolled(members) {
    const container = document.getElementById("panelContent");

    if (members.length === 0) {
        container.innerHTML = `
            <div class="panel-empty">
                <i data-lucide="user-x" style="width:24px;height:24px;color:#333;display:block;margin:0 auto 8px;"></i>
                No active members in this class.
            </div>`;
        lucide.createIcons();
        return;
    }

    let html = `<input type="text" class="panel-search" placeholder="Search enrolled members..."
                    oninput="filterEnrolled(this.value)">`;
    html += `<div id="membersList">`;
    members.forEach(m => {
        const initials = getInitials(m.member_name);
        html += `
            <div class="member-item">
                <div class="member-avatar">${initials}</div>
                <div class="member-info">
                    <div class="name">${escapeHtml(m.member_name)}</div>
                    <div class="since">Enrolled ${escapeHtml(m.enrolled_at)}</div>
                </div>
            </div>`;
    });
    html += `</div>`;

    container.innerHTML = html;
    lucide.createIcons();
}

function filterEnrolled(query) {
    const q        = query.toLowerCase();
    const filtered = enrolledCache.filter(m => m.member_name.toLowerCase().includes(q));
    const list     = document.getElementById("membersList");
    if (!list) return;

    if (filtered.length === 0) {
        list.innerHTML = `<div style="text-align:center;color:#555;padding:20px;font-size:13px;">No results.</div>`;
        return;
    }

    list.innerHTML = filtered.map(m => {
        const initials = getInitials(m.member_name);
        return `
            <div class="member-item">
                <div class="member-avatar">${initials}</div>
                <div class="member-info">
                    <div class="name">${escapeHtml(m.member_name)}</div>
                    <div class="since">Enrolled ${escapeHtml(m.enrolled_at)}</div>
                </div>
            </div>`;
    }).join("");
}

// ── Pending tab ───────────────────────────────────────────
function renderPending(members) {
    const container = document.getElementById("panelContent");

    if (members.length === 0) {
        container.innerHTML = `
            <div class="panel-empty">
                <i data-lucide="check-circle" style="width:24px;height:24px;color:#333;display:block;margin:0 auto 8px;"></i>
                No pending requests.
            </div>`;
        lucide.createIcons();
        return;
    }

    let html = `<div id="pendingList">`;
    members.forEach(m => {
        const initials = getInitials(m.member_name);
        html += `
            <div class="pending-item" id="pending-item-${m.id}">
                <div class="pending-avatar">${initials}</div>
                <div class="pending-info">
                    <div class="name">${escapeHtml(m.member_name)}</div>
                    <div class="since">Requested ${escapeHtml(m.enrolled_at)}</div>
                </div>
                <div class="pending-actions">
                    <button class="btn-approve-sm" onclick="reviewEnrollment(${m.id}, 'approve')">
                        <i data-lucide="check"></i> Approve
                    </button>
                    <button class="btn-reject-sm" onclick="reviewEnrollment(${m.id}, 'reject')">
                        <i data-lucide="x"></i> Reject
                    </button>
                </div>
            </div>`;
    });
    html += `</div>`;

    container.innerHTML = html;
    lucide.createIcons();
}

// ── Review enrollment (AJAX) ──────────────────────────────
function reviewEnrollment(enrollmentId, action) {
    const item = document.getElementById("pending-item-" + enrollmentId);
    if (item) {
        item.style.opacity       = "0.4";
        item.style.pointerEvents = "none";
    }

    const formData = new FormData();
    formData.append("id",     enrollmentId);
    formData.append("action", action);

    fetch("./controllers/AdminController/review-enrollment.php", {
        method: "POST",
        body:   formData,
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Remove from pendingCache
            const idx = pendingCache.findIndex(m => m.id == enrollmentId);
            if (idx !== -1) {
                const [member] = pendingCache.splice(idx, 1);

                // ✅ Only move to enrolled if approved
                if (action === "approve") {
                    member.status = "approved";
                    enrolledCache.push(member);
                }
                // If rejected — member is just removed from pending, gone from both lists
            }

            updateTabCounts();
            updateClassRowPill(activeClassId, pendingCache.length);

            // Re-render the pending tab (stay on it so admin can keep reviewing)
            renderTab();

            showToast(data.message, "success");
        } else {
            if (item) { item.style.opacity = "1"; item.style.pointerEvents = ""; }
            showToast(data.message || "Something went wrong.", "error");
        }
    })
    .catch(() => {
        if (item) { item.style.opacity = "1"; item.style.pointerEvents = ""; }
        showToast("Network error. Please try again.", "error");
    });
}

// ── Update the pending pill on the class row ──────────────
function updateClassRowPill(classId, pendingCount) {
    const row = document.getElementById("class-row-" + classId);
    if (!row) return;

    const nameEl = row.querySelector(".class-name");
    let   pill   = nameEl.querySelector(".pending-pill");

    if (pendingCount > 0) {
        if (!pill) {
            pill = document.createElement("span");
            pill.className = "pending-pill";
            nameEl.appendChild(pill);
        }
        pill.textContent = pendingCount + " pending";
    } else if (pill) {
        pill.remove();
    }
}

// ── Toast ─────────────────────────────────────────────────
let toastTimer = null;
function showToast(msg, type = "success") {
    const bar   = document.getElementById("toastBar");
    bar.textContent = msg;
    bar.className   = `toast-bar toast-${type} show`;

    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => bar.classList.remove("show"), 3500);
}

// ── Helpers ───────────────────────────────────────────────
function getInitials(name) {
    return String(name).split(" ").map(w => w[0]).join("").substring(0, 2).toUpperCase();
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g,  "&amp;")
        .replace(/</g,  "&lt;")
        .replace(/>/g,  "&gt;")
        .replace(/"/g,  "&quot;")
        .replace(/'/g,  "&#39;");
}

// ── Modal population ──────────────────────────────────────
document.getElementById("updateClassModal").addEventListener("show.bs.modal", function(e) {
    const btn = e.relatedTarget;
    document.getElementById("edit_id").value          = btn.dataset.id;
    document.getElementById("edit_name").value        = btn.dataset.name;
    document.getElementById("edit_instructor").value  = btn.dataset.instructor;
    document.getElementById("edit_description").value = btn.dataset.description;
    document.getElementById("edit_day").value         = btn.dataset.day;
    document.getElementById("edit_time").value        = btn.dataset.time;
});

document.getElementById("deleteClassModal").addEventListener("show.bs.modal", function(e) {
    const btn = e.relatedTarget;
    document.getElementById("delete_id").value         = btn.dataset.id;
    document.getElementById("delete_name").textContent = btn.dataset.name;
});
</script>
</body>
</html>