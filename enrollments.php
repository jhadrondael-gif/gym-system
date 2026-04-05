<?php
require_once("./controllers/AdminController/get-enrollments.php");
$enrollments = $response["data"] ?? [];

require_once("./controllers/AdminController/get-members.php");
$members = $response["data"] ?? [];

require_once("./controllers/AdminController/get-classes.php");
$classes = $response["data"] ?? [];

$success = $_SESSION["success"] ?? null;
$error   = $_SESSION["error"]   ?? null;
unset($_SESSION["success"], $_SESSION["error"]);

// Counts for summary cards
$total    = count($enrollments);
$pending  = count(array_filter($enrollments, fn($e) => $e["status"] === "pending"));
$approved = count(array_filter($enrollments, fn($e) => $e["status"] === "approved"));
$rejected = count(array_filter($enrollments, fn($e) => $e["status"] === "rejected"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Enrollments</title>
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

        /* Summary cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin: 20px 0;
        }

        .summary-card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .summary-card .icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .summary-card .count {
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
        }

        .summary-card .label {
            font-size: 12px;
            color: #888;
            margin-top: 2px;
        }

        /* Table card */
        .card {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            padding: 20px;
            border-radius: 10px;
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

        .table thead th:last-child { border-right: none; }

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

        .table tbody td:last-child { border-right: none; }

        .table tbody tr:hover { background: #1a1a1a !important; }
        .table tbody tr:hover td { background: #1a1a1a !important; }

        .empty-row td {
            text-align: center;
            color: #888;
            padding: 30px;
        }

        /* Status badges */
        .badge-pending  { background: #e6a817; color: #000; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-approved { background: #28a745; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .badge-rejected { background: #dc3545; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; }

        /* Source badge */
        .badge-admin  { background: #6c3cbf; color: #fff; padding: 3px 8px; border-radius: 20px; font-size: 11px; }
        .badge-member { background: #1a7abf; color: #fff; padding: 3px 8px; border-radius: 20px; font-size: 11px; }

        /* Buttons */
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

        .btn-approve {
            background: transparent;
            border: 1px solid #28a745;
            color: #28a745;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-approve:hover { background: #28a745; color: #fff; }

        .btn-reject {
            background: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            transition: 0.2s;
            margin-left: 3px;
        }

        .btn-reject:hover { background: #dc3545; color: #fff; }

        .btn-delete {
            background: transparent;
            border: 1px solid #555;
            color: #888;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            transition: 0.2s;
            margin-left: 3px;
        }

        .btn-delete:hover { background: #555; color: #fff; }

        /* Filter bar */
        .filter-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-bar input,
        .filter-bar select {
            background: #111;
            border: 1px solid #333;
            color: #fff;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 13px;
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: #ffd700;
        }

        .filter-bar select option { background: #1a1a1a; }

        /* Modal */
        .modal-content { background: #1a1a1a; border: 1px solid #ffd700; color: #fff; }
        .modal-header  { border-bottom: 1px solid #333; }
        .modal-footer  { border-top: 1px solid #333; }
        .modal-title   { color: #ffd700; font-weight: 600; }
        .btn-close     { filter: invert(1); }
        .form-label    { color: #ccc; font-size: 14px; }

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
            box-shadow: 0 0 0 0.2rem rgba(255,215,0,0.2);
        }

        .form-select option { background: #1a1a1a; }
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
    <a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollments.php" class="active"><i data-lucide="clipboard-list"></i> Enrollments</a>
    <a href="./controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>ENROLLMENTS</h2>
            <p style="color:#888;margin:0;">Manage and review all class enrollments.</p>
        </div>
        <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#createEnrollmentModal">
            <i data-lucide="plus" style="width:15px;height:15px;"></i> Enroll Member
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

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="icon" style="background:#222;">
                <i data-lucide="clipboard-list" style="width:20px;height:20px;color:#ffd700;"></i>
            </div>
            <div>
                <div class="count" style="color:#ffd700;"><?= $total ?></div>
                <div class="label">Total Enrollments</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon" style="background:#2a2000;">
                <i data-lucide="clock" style="width:20px;height:20px;color:#e6a817;"></i>
            </div>
            <div>
                <div class="count" style="color:#e6a817;"><?= $pending ?></div>
                <div class="label">Pending</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon" style="background:#0a2a0a;">
                <i data-lucide="check-circle" style="width:20px;height:20px;color:#28a745;"></i>
            </div>
            <div>
                <div class="count" style="color:#28a745;"><?= $approved ?></div>
                <div class="label">Approved</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon" style="background:#2a0a0a;">
                <i data-lucide="x-circle" style="width:20px;height:20px;color:#dc3545;"></i>
            </div>
            <div>
                <div class="count" style="color:#dc3545;"><?= $rejected ?></div>
                <div class="label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="Search member or class..." oninput="filterTable()">
            <select id="statusFilter" onchange="filterTable()">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <select id="sourceFilter" onchange="filterTable()">
                <option value="">All Sources</option>
                <option value="admin">Admin</option>
                <option value="member">Member</option>
            </select>
            <span id="recordCount" style="color:#555;font-size:12px;margin-left:auto;"></span>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Member</th>
                        <th>Class</th>
                        <th>Schedule</th>
                        <th>Source</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($enrollments)): ?>
                        <tr class="empty-row">
                            <td colspan="8">No enrollments found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($enrollments as $i => $e): ?>
                            <tr id="row-<?= $e['id'] ?>"
                                data-search="<?= strtolower(htmlspecialchars($e['member_name'] . ' ' . $e['class_name'])) ?>"
                                data-status="<?= $e['status'] ?>"
                                data-source="<?= $e['initiated_by'] ?>">
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($e['member_name']) ?></td>
                                <td>
                                    <?= htmlspecialchars($e['class_name']) ?>
                                    <div style="font-size:11px;color:#666;"><?= htmlspecialchars($e['instructor']) ?></div>
                                </td>
                                <td style="font-size:12px;"><?= htmlspecialchars($e['day']) ?> &bull; <?= htmlspecialchars($e['class_time']) ?></td>
                                <td>
                                    <?php if ($e['initiated_by'] === 'admin'): ?>
                                        <span class="badge-admin">Admin</span>
                                    <?php else: ?>
                                        <span class="badge-member">Member</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size:12px;"><?= htmlspecialchars($e['enrolled_at']) ?></td>
                                <td id="status-<?= $e['id'] ?>">
                                    <?php if ($e['status'] === 'pending'): ?>
                                        <span class="badge-pending">Pending</span>
                                    <?php elseif ($e['status'] === 'approved'): ?>
                                        <span class="badge-approved">Approved</span>
                                    <?php else: ?>
                                        <span class="badge-rejected">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($e['status'] === 'pending'): ?>
                                        <button class="btn-approve" onclick="updateStatus(<?= $e['id'] ?>, 'approved')">
                                            <i data-lucide="check" style="width:11px;height:11px;"></i> Approve
                                        </button>
                                        <button class="btn-reject" onclick="updateStatus(<?= $e['id'] ?>, 'rejected')">
                                            <i data-lucide="x" style="width:11px;height:11px;"></i> Reject
                                        </button>
                                    <?php elseif ($e['status'] === 'approved'): ?>
                                        <button class="btn-reject" onclick="updateStatus(<?= $e['id'] ?>, 'rejected')">
                                            <i data-lucide="x" style="width:11px;height:11px;"></i> Reject
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-approve" onclick="updateStatus(<?= $e['id'] ?>, 'approved')">
                                            <i data-lucide="check" style="width:11px;height:11px;"></i> Approve
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteEnrollmentModal"
                                        data-id="<?= $e['id'] ?>"
                                        data-member="<?= htmlspecialchars($e['member_name']) ?>"
                                        data-class="<?= htmlspecialchars($e['class_name']) ?>">
                                        <i data-lucide="trash-2" style="width:11px;height:11px;"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Enrollment Modal -->
<div class="modal fade" id="createEnrollmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enroll a Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/create-enrollment.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Member</label>
                            <select name="member_id" class="form-select" required>
                                <option value="" disabled selected>Select member</option>
                                <?php foreach ($members as $m): ?>
                                    <option value="<?= $m['id'] ?>">
                                        <?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Class</label>
                            <select name="class_id" class="form-select" required>
                                <option value="" disabled selected>Select class</option>
                                <?php foreach ($classes as $c): ?>
                                    <option value="<?= $c['id'] ?>">
                                        <?= htmlspecialchars($c['name']) ?> &mdash; <?= htmlspecialchars($c['day']) ?> <?= htmlspecialchars($c['time']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Initial Status</label>
                            <select name="status" class="form-select" required>
                                <option value="approved" selected>Approved</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Enroll</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Enrollment Modal -->
<div class="modal fade" id="deleteEnrollmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/delete-enrollment.php" method="POST">
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-body">
                    <p>Remove <strong id="delete_member" style="color:#ffd700;"></strong> from <strong id="delete_class" style="color:#ffd700;"></strong>? This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();

    // Filter table
    function filterTable() {
        const search = document.getElementById("searchInput").value.toLowerCase();
        const status = document.getElementById("statusFilter").value;
        const source = document.getElementById("sourceFilter").value;
        const rows   = document.querySelectorAll("#tableBody tr[data-search]");
        let visible  = 0;

        rows.forEach(row => {
            const matchSearch = row.dataset.search.includes(search);
            const matchStatus = !status || row.dataset.status === status;
            const matchSource = !source || row.dataset.source === source;
            const show = matchSearch && matchStatus && matchSource;
            row.style.display = show ? "" : "none";
            if (show) visible++;
        });

        document.getElementById("recordCount").textContent =
            visible + " record" + (visible !== 1 ? "s" : "") + " shown";
    }

    // Approve / Reject inline without page reload
    function updateStatus(id, status) {
        fetch("./controllers/AdminController/update-enrollment.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, status })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { alert(data.message); return; }

            // Update badge
            const badgeEl = document.getElementById("status-" + id);
            const badgeMap = {
                approved: '<span class="badge-approved">Approved</span>',
                rejected: '<span class="badge-rejected">Rejected</span>',
                pending:  '<span class="badge-pending">Pending</span>'
            };
            badgeEl.innerHTML = badgeMap[status];

            // Update action buttons
            const row = document.getElementById("row-" + id);
            row.dataset.status = status;
            const actionsCell = row.cells[row.cells.length - 1];
            const deleteBtn   = actionsCell.querySelector(".btn-delete").outerHTML;

            let actionBtns = "";
            if (status === "pending") {
                actionBtns = `
                    <button class="btn-approve" onclick="updateStatus(${id}, 'approved')">
                        <i data-lucide="check" style="width:11px;height:11px;"></i> Approve
                    </button>
                    <button class="btn-reject" onclick="updateStatus(${id}, 'rejected')">
                        <i data-lucide="x" style="width:11px;height:11px;"></i> Reject
                    </button>`;
            } else if (status === "approved") {
                actionBtns = `
                    <button class="btn-reject" onclick="updateStatus(${id}, 'rejected')">
                        <i data-lucide="x" style="width:11px;height:11px;"></i> Reject
                    </button>`;
            } else {
                actionBtns = `
                    <button class="btn-approve" onclick="updateStatus(${id}, 'approved')">
                        <i data-lucide="check" style="width:11px;height:11px;"></i> Approve
                    </button>`;
            }

            actionsCell.innerHTML = actionBtns + deleteBtn;
            lucide.createIcons();
            filterTable();
        })
        .catch(() => alert("Network error. Please try again."));
    }

    // Delete modal
    const deleteModal = document.getElementById("deleteEnrollmentModal");
    deleteModal.addEventListener("show.bs.modal", function(e) {
        const btn = e.relatedTarget;
        document.getElementById("delete_id").value             = btn.dataset.id;
        document.getElementById("delete_member").textContent   = btn.dataset.member;
        document.getElementById("delete_class").textContent    = btn.dataset.class;
    });

    filterTable();
</script>
</body>
</html>