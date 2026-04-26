<?php
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

        .empty-row td {
            text-align: center;
            color: #888;
            padding: 30px;
        }

        /* Modal overrides */
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
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="dashboard.php"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="member.php" class="active"><i data-lucide="users"></i> Members</a>
    <a href="membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="attendance.php"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="enrollments.php"><i data-lucide="settings"></i> Enrollments</a>
    <a href="./controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center">
        <h2>MEMBERS</h2>
        <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#createMemberModal">
            <i data-lucide="user-plus" style="width:16px;height:16px;margin-right:6px;"></i> Add Member
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
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr class="empty-row">
                            <td colspan="8">No members found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?= htmlspecialchars($member["id"]) ?></td>
                                <td><?= htmlspecialchars($member["first_name"] . " " . $member["last_name"]) ?></td>
                                <td><?= htmlspecialchars($member["gender"]) ?></td>
                                <td><?= htmlspecialchars($member["birthdate"]) ?></td>
                                <td><?= htmlspecialchars($member["contact_number"]) ?></td>
                                <td><?= htmlspecialchars($member["email"]) ?></td>
                                <td>
                                    <?php if (strtolower($member["status"]) === "active"): ?>
                                        <span class="badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateMemberModal"
                                        data-id="<?= $member['id'] ?>"
                                        data-first_name="<?= htmlspecialchars($member['first_name']) ?>"
                                        data-last_name="<?= htmlspecialchars($member['last_name']) ?>"
                                        data-gender="<?= htmlspecialchars($member['gender']) ?>"
                                        data-birthdate="<?= htmlspecialchars($member['birthdate']) ?>"
                                        data-contact_number="<?= htmlspecialchars($member['contact_number']) ?>"
                                        data-email="<?= htmlspecialchars($member['email']) ?>"
                                        data-status="<?= htmlspecialchars($member['status']) ?>"
                                        data-role="<?= htmlspecialchars($member['role']) ?>">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i> Edit
                                    </button>
                                    <!-- Delete Button -->
                                    <button class="btn-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteMemberModal"
                                        data-id="<?= $member['id'] ?>"
                                        data-name="<?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>">
                                        <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
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

<!-- Create Member Modal -->
<div class="modal fade" id="createMemberModal" tabindex="-1" aria-labelledby="createMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMemberModalLabel">Add New Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/create-member.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="" disabled selected>Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="birthdate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="" disabled selected>Select status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="" disabled selected>Select role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Create Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Member Modal -->
<div class="modal fade" id="updateMemberModal" tabindex="-1" aria-labelledby="updateMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateMemberModalLabel">Edit Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/update-member.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" id="edit_first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="edit_last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" id="edit_gender" class="form-select" required>
                                <option value="" disabled>Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="birthdate" id="edit_birthdate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" id="edit_contact_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="" disabled>Select status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" id="edit_role" class="form-select" required>
                                <option value="" disabled>Select role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
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
<div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMemberModalLabel">Delete Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="./controllers/AdminController/delete-member.php" method="POST">
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="delete_name" style="color:#ffd700;"></strong>? This action cannot be undone.</p>
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

    // Populate update modal
    const updateModal = document.getElementById("updateMemberModal");
    updateModal.addEventListener("show.bs.modal", function (e) {
        const btn = e.relatedTarget;
        document.getElementById("edit_id").value             = btn.dataset.id;
        document.getElementById("edit_first_name").value     = btn.dataset.first_name;
        document.getElementById("edit_last_name").value      = btn.dataset.last_name;
        document.getElementById("edit_birthdate").value      = btn.dataset.birthdate;
        document.getElementById("edit_contact_number").value = btn.dataset.contact_number;
        document.getElementById("edit_email").value          = btn.dataset.email;
        document.getElementById("edit_gender").value         = btn.dataset.gender;
        document.getElementById("edit_status").value         = btn.dataset.status;
        document.getElementById("edit_role").value           = btn.dataset.role;
    });

    // Populate delete modal
    const deleteModal = document.getElementById("deleteMemberModal");
    deleteModal.addEventListener("show.bs.modal", function (e) {
        const btn = e.relatedTarget;
        document.getElementById("delete_id").value   = btn.dataset.id;
        document.getElementById("delete_name").textContent = btn.dataset.name;
    });
</script>
</body>
</html>