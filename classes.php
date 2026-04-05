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
    <title>Classes</title>
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

        /* Layout: classes list + members panel */
        .classes-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
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

        .class-row:last-child {
            margin-bottom: 0;
        }

        .class-row:hover {
            border-color: #ffd700;
            background: #1a1a0a;
        }

        .class-row.active {
            border-color: #ffd700;
            background: #1a1a0a;
        }

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

        .class-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .class-meta svg {
            width: 12px;
            height: 12px;
        }

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

        .empty-classes {
            text-align: center;
            color: #555;
            padding: 40px 0;
            font-size: 14px;
        }

        /* Members panel */
        .members-panel {
            position: sticky;
            top: 30px;
        }

        .members-panel .card-title span {
            font-size: 13px;
            color: #888;
            font-weight: 400;
        }

        .member-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #222;
        }

        .member-item:last-child {
            border-bottom: none;
        }

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

        .member-info .name {
            font-size: 14px;
            font-weight: 500;
            color: #fff;
        }

        .member-info .since {
            font-size: 11px;
            color: #666;
            margin-top: 1px;
        }

        .panel-empty {
            text-align: center;
            color: #555;
            padding: 40px 0;
            font-size: 13px;
        }

        .panel-placeholder {
            text-align: center;
            color: #444;
            padding: 50px 10px;
            font-size: 13px;
            line-height: 1.8;
        }

        .panel-placeholder svg {
            width: 32px;
            height: 32px;
            color: #333;
            margin-bottom: 10px;
        }

        .loading-text {
            text-align: center;
            color: #555;
            padding: 40px 0;
            font-size: 13px;
        }

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

        .class-actions {
            display: flex;
            gap: 4px;
            margin-top: 8px;
        }

        /* Modal */
        .modal-content {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            color: #fff;
        }

        .modal-header { border-bottom: 1px solid #333; }
        .modal-footer { border-top: 1px solid #333; }

        .modal-title {
            color: #ffd700;
            font-weight: 600;
        }

        .btn-close { filter: invert(1); }

        .form-label {
            color: #ccc;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            background: #111;
            border: 1px solid #444;
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background: #111;
            border-color: #ffd700;
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.2);
        }

        .form-select option { background: #1a1a1a; }

        textarea.form-control { resize: vertical; min-height: 80px; }

        /* Search in panel */
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

        .panel-search:focus {
            outline: none;
            border-color: #ffd700;
        }
    </style>
</head>
<body>

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
<<<<<<< HEAD
    <h2>Classes</h2>

=======

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
                            <div class="class-name"><?= htmlspecialchars($class['name']) ?></div>
                            <div class="class-meta">
                                <span>
                                    <i data-lucide="user" ></i>
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

                <div id="panelContent">
                    <div class="panel-placeholder">
                        <i data-lucide="mouse-pointer-click"></i>
                        <br>Select a class on the left<br>to view its members.
                    </div>
                </div>
            </div>
        </div>

    </div>
>>>>>>> cb2fcb3e9e720e9cb5b5fcf94bd090df8257168c
</div>

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
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                                <option>Sunday</option>
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
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                                <option>Sunday</option>
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

    let activeClassId   = null;
    let allMembersCache = [];

    // Load enrolled members for a class
    function loadMembers(classId, className, count) {
        // Highlight active row
        document.querySelectorAll(".class-row").forEach(r => r.classList.remove("active"));
        document.getElementById("class-row-" + classId).classList.add("active");

        activeClassId = classId;

        document.getElementById("panelTitle").textContent =
            className + " (" + count + ")";

        document.getElementById("panelContent").innerHTML =
            `<div class="loading-text">Loading...</div>`;

        fetch(`./controllers/AdminController/get-class-members.php?class_id=${classId}`)
            .then(r => r.json())
            .then(data => {
                allMembersCache = data.data || [];
                renderMembers(allMembersCache);
            })
            .catch(() => {
                document.getElementById("panelContent").innerHTML =
                    `<div class="panel-empty">Failed to load members.</div>`;
            });
    }

    function renderMembers(members) {
        const container = document.getElementById("panelContent");

        if (members.length === 0) {
            container.innerHTML = `
                <div class="panel-empty">
                    <i data-lucide="user-x" style="width:24px;height:24px;color:#333;display:block;margin:0 auto 8px;"></i>
                    No members enrolled in this class.
                </div>`;
            lucide.createIcons();
            return;
        }

        let html = `<input
            type="text"
            class="panel-search"
            placeholder="Search members..."
            oninput="filterMembers(this.value)"
        >`;

        html += `<div id="membersList">`;
        members.forEach(m => {
            const initials = m.member_name.split(" ").map(w => w[0]).join("").substring(0, 2).toUpperCase();
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

    function filterMembers(query) {
        const q       = query.toLowerCase();
        const filtered = allMembersCache.filter(m =>
            m.member_name.toLowerCase().includes(q)
        );

        const list = document.getElementById("membersList");
        if (!list) return;

        if (filtered.length === 0) {
            list.innerHTML = `<div style="text-align:center;color:#555;padding:20px;font-size:13px;">No results found.</div>`;
            return;
        }

        list.innerHTML = filtered.map(m => {
            const initials = m.member_name.split(" ").map(w => w[0]).join("").substring(0, 2).toUpperCase();
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

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }

    // Populate update modal
    const updateModal = document.getElementById("updateClassModal");
    updateModal.addEventListener("show.bs.modal", function(e) {
        const btn = e.relatedTarget;
        document.getElementById("edit_id").value          = btn.dataset.id;
        document.getElementById("edit_name").value        = btn.dataset.name;
        document.getElementById("edit_instructor").value  = btn.dataset.instructor;
        document.getElementById("edit_description").value = btn.dataset.description;
        document.getElementById("edit_day").value         = btn.dataset.day;
        document.getElementById("edit_time").value        = btn.dataset.time;
    });

    // Populate delete modal
    const deleteModal = document.getElementById("deleteClassModal");
    deleteModal.addEventListener("show.bs.modal", function(e) {
        const btn = e.relatedTarget;
        document.getElementById("delete_id").value       = btn.dataset.id;
        document.getElementById("delete_name").textContent = btn.dataset.name;
    });
</script>
</body>
</html>