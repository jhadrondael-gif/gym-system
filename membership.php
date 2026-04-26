<?php
require_once("./controllers/AdminController/get-memberships.php");
$memberships = $response["data"] ?? [];

// Also fetch members list for the dropdown
require_once("./controllers/AdminController/get-members.php");
$members = $response["data"] ?? [];

$success = $_SESSION["success"] ?? null;
$error   = $_SESSION["error"] ?? null;
unset($_SESSION["success"], $_SESSION["error"]);
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

        /* ── Badges ── */
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

        .badge-pending {
            background: #2a1f00;
            color: #f59e0b;
            border: 1px solid #f59e0b44;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .empty-row td {
            text-align: center;
            color: #888;
            padding: 30px;
        }

        /* ── Modal overrides ── */
        .modal-content {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            color: #fff;
        }

        .modal-header {
            border-bottom: 1px solid #333;
        }

        .modal-footer {
            border-top: 1px solid #333;
        }

        .modal-title {
            color: #ffd700;
            font-weight: 600;
        }

        .btn-close {
            filter: invert(1);
        }

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

        .form-select option {
            background: #1a1a1a;
        }

        .btn-gold {
            background: #ffd700;
            color: #000;
            font-weight: 600;
            border: none;
        }

        .btn-gold:hover {
            background: #e6c200;
            color: #000;
        }

        /* ── Action buttons ── */
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

        .btn-edit:hover {
            background: #ffd700;
            color: #000;
        }

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

        .btn-delete:hover {
            background: #dc3545;
            color: #fff;
        }

        .btn-approve {
            background: transparent;
            border: 1px solid #22c55e;
            color: #22c55e;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-approve:hover {
            background: #22c55e;
            color: #000;
        }

        .btn-reject {
            background: transparent;
            border: 1px solid #f59e0b;
            color: #f59e0b;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.2s;
            margin-left: 4px;
        }

        .btn-reject:hover {
            background: #f59e0b;
            color: #000;
        }

        /* ── Review modal info box ── */
        .review-info {
            background: #111;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 13px;
        }

        .review-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #1f1f1f;
            padding-bottom: 6px;
        }

        .review-info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .review-info-label {
            color: #666;
        }

        .review-info-value {
            color: #fff;
            font-weight: 600;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="dashboard.php"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="member.php"><i data-lucide="users"></i> Members</a>
    <a href="membership.php" class="active"><i data-lucide="credit-card"></i> Membership</a>
    <a href="attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollments.php"><i data-lucide="settings"></i> Enrollments</a>
    <a href="./controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center">
        <h2>MEMBERSHIP</h2>
        <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#createMembershipModal">
            <i data-lucide="plus" style="width:16px;height:16px;margin-right:6px;"></i> Add Membership
        </button>
    </div>
    <p>Select an option from the sidebar.</p>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($memberships)): ?>
                        <tr class="empty-row">
                            <td colspan="8">No memberships found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($memberships as $ms): ?>
                            <tr>
                                <td><?= htmlspecialchars($ms["id"]) ?></td>
                                <td><?= htmlspecialchars($ms["member_name"]) ?></td>
                                <td><?= htmlspecialchars($ms["type"]) ?></td>
                                <td><?= htmlspecialchars($ms["start_date"]) ?></td>
                                <td><?= htmlspecialchars($ms["end_date"]) ?></td>
                                <td>
                                    <?php $status = strtolower($ms["status"]); ?>
                                    <?php if ($status === "active"): ?>
                                        <span class="badge-active">Active</span>
                                    <?php elseif ($status === "expired"): ?>
                                        <span class="badge-expired">Expired</span>
                                    <?php elseif ($status === "pending"): ?>
                                        <span class="badge-pending">Pending</span>
                                    <?php else: ?>
                                        <span class="badge-cancelled">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                                <td>&#8369;<?= number_format($ms["fee"], 2) ?></td>
                                <td>
                                    <?php if ($status === "pending"): ?>
                                        <!-- Approve -->
                                        <button class="btn-approve"
                                            data-bs-toggle="modal"
                                            data-bs-target="#reviewMembershipModal"
                                            data-id="<?= $ms['id'] ?>"
                                            data-action="approve"
                                            data-member_name="<?= htmlspecialchars($ms['member_name']) ?>"
                                            data-type="<?= htmlspecialchars($ms['type']) ?>"
                                            data-start_date="<?= $ms['start_date'] ?>"
                                            data-end_date="<?= $ms['end_date'] ?>"
                                            data-fee="<?= $ms['fee'] ?>">
                                            <i data-lucide="check" style="width:13px;height:13px;"></i> Approve
                                        </button>
                                        <!-- Reject -->
                                        <button class="btn-reject"
                                            data-bs-toggle="modal"
                                            data-bs-target="#reviewMembershipModal"
                                            data-id="<?= $ms['id'] ?>"
                                            data-action="reject"
                                            data-member_name="<?= htmlspecialchars($ms['member_name']) ?>"
                                            data-type="<?= htmlspecialchars($ms['type']) ?>"
                                            data-start_date="<?= $ms['start_date'] ?>"
                                            data-end_date="<?= $ms['end_date'] ?>"
                                            data-fee="<?= $ms['fee'] ?>">
                                            <i data-lucide="x" style="width:13px;height:13px;"></i> Reject
                                        </button>
                                    <?php else: ?>
                                        <!-- Edit -->
                                        <button class="btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#updateMembershipModal"
                                            data-id="<?= $ms['id'] ?>"
                                            data-member_id="<?= $ms['member_id'] ?>"
                                            data-type="<?= htmlspecialchars($ms['type']) ?>"
                                            data-start_date="<?= $ms['start_date'] ?>"
                                            data-end_date="<?= $ms['end_date'] ?>"
                                            data-status="<?= htmlspecialchars($ms['status']) ?>"
                                            data-fee="<?= $ms['fee'] ?>">
                                            <i data-lucide="pencil" style="width:13px;height:13px;"></i> Edit
                                        </button>
                                        <!-- Delete -->
                                        <button class="btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteMembershipModal"
                                            data-id="<?= $ms['id'] ?>"
                                            data-member_name="<?= htmlspecialchars($ms['member_name']) ?>">
                                            <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
                                        </button>
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

<!-- ══════════════════════════════════════════════════════════ -->
<!-- Review (Approve / Reject) Membership Modal               -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="reviewMembershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="review_modal_title">Review Membership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/review-membership.php" method="POST">
                <input type="hidden" name="id"     id="review_id">
                <input type="hidden" name="action" id="review_action">
                <div class="modal-body">
                    <p id="review_modal_desc" style="font-size:13px;color:#ccc;margin-bottom:14px;"></p>
                    <div class="review-info">
                        <div class="review-info-row">
                            <span class="review-info-label">Member</span>
                            <span class="review-info-value" id="review_member_name"></span>
                        </div>
                        <div class="review-info-row">
                            <span class="review-info-label">Plan</span>
                            <span class="review-info-value" id="review_type"></span>
                        </div>
                        <div class="review-info-row">
                            <span class="review-info-label">Start Date</span>
                            <span class="review-info-value" id="review_start_date"></span>
                        </div>
                        <div class="review-info-row">
                            <span class="review-info-label">End Date</span>
                            <span class="review-info-value" id="review_end_date"></span>
                        </div>
                        <div class="review-info-row">
                            <span class="review-info-label">Fee</span>
                            <span class="review-info-value" id="review_fee"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" id="review_submit_btn">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Membership Modal -->
<div class="modal fade" id="createMembershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Membership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/create-membership.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Member</label>
                            <select name="member_id" class="form-select" required>
                                <option value="" disabled selected>Select member</option>
                                <?php foreach ($members as $member): ?>
                                    <option value="<?= $member['id'] ?>">
                                        <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="" disabled selected>Select type</option>
                                <option value="Basic">Basic</option>
                                <option value="Premium">Premium</option>
                                <option value="VIP">VIP</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="" disabled selected>Select status</option>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Fee (₱)</label>
                            <input type="number" name="fee" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Create Membership</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Membership Modal -->
<div class="modal fade" id="updateMembershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Membership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/update-membership.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Member</label>
                            <select name="member_id" id="edit_member_id" class="form-select" required>
                                <option value="" disabled>Select member</option>
                                <?php foreach ($members as $member): ?>
                                    <option value="<?= $member['id'] ?>">
                                        <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type" id="edit_type" class="form-select" required>
                                <option value="" disabled>Select type</option>
                                <option value="Basic">Basic</option>
                                <option value="Premium">Premium</option>
                                <option value="VIP">VIP</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="" disabled>Select status</option>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Fee (₱)</label>
                            <input type="number" name="fee" id="edit_fee" class="form-control" step="0.01" min="0" required>
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

<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteMembershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Membership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/delete-membership.php" method="POST">
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-body">
                    <p>Are you sure you want to delete the membership of
                        <strong id="delete_member_name" style="color:#ffd700;"></strong>?
                        This action cannot be undone.
                    </p>
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

    // ── Review (Approve / Reject) modal ──────────────────────
    document.getElementById("reviewMembershipModal").addEventListener("show.bs.modal", function (e) {
        const btn    = e.relatedTarget;
        const action = btn.dataset.action;           // 'approve' | 'reject'
        const name   = btn.dataset.member_name;
        const type   = btn.dataset.type;

        document.getElementById("review_id").value     = btn.dataset.id;
        document.getElementById("review_action").value = action;

        // Info rows
        document.getElementById("review_member_name").textContent = name;
        document.getElementById("review_type").textContent        = type;
        document.getElementById("review_start_date").textContent  = btn.dataset.start_date;
        document.getElementById("review_end_date").textContent    = btn.dataset.end_date;
        document.getElementById("review_fee").textContent         = "₱" + parseFloat(btn.dataset.fee).toLocaleString("en-PH", { minimumFractionDigits: 0 });

        const submitBtn = document.getElementById("review_submit_btn");

        if (action === "approve") {
            document.getElementById("review_modal_title").textContent = "Approve Membership";
            document.getElementById("review_modal_desc").textContent  =
                `Approving this request will activate the ${type} plan for ${name}. Any existing active membership will be cancelled automatically.`;
            submitBtn.textContent  = "Approve";
            submitBtn.className    = "btn btn-success fw-bold";
        } else {
            document.getElementById("review_modal_title").textContent = "Reject Membership";
            document.getElementById("review_modal_desc").textContent  =
                `Are you sure you want to reject the ${type} plan request from ${name}? The request will be marked as cancelled.`;
            submitBtn.textContent  = "Reject";
            submitBtn.className    = "btn btn-danger fw-bold";
        }
    });

    // ── Update modal ─────────────────────────────────────────
    document.getElementById("updateMembershipModal").addEventListener("show.bs.modal", function (e) {
        const btn = e.relatedTarget;
        document.getElementById("edit_id").value         = btn.dataset.id;
        document.getElementById("edit_member_id").value  = btn.dataset.member_id;
        document.getElementById("edit_type").value       = btn.dataset.type;
        document.getElementById("edit_start_date").value = btn.dataset.start_date;
        document.getElementById("edit_end_date").value   = btn.dataset.end_date;
        document.getElementById("edit_status").value     = btn.dataset.status;
        document.getElementById("edit_fee").value        = btn.dataset.fee;
    });

    // ── Delete modal ─────────────────────────────────────────
    document.getElementById("deleteMembershipModal").addEventListener("show.bs.modal", function (e) {
        const btn = e.relatedTarget;
        document.getElementById("delete_id").value                = btn.dataset.id;
        document.getElementById("delete_member_name").textContent = btn.dataset.member_name;
    });
</script>
</body>
</html>