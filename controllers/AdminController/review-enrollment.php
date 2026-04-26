<?php
// controllers/AdminController/review-enrollment.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auth guard — admin only
if (!isset($_SESSION['admin']) || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../db/Database.php';

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id     = isset($_POST['id'])     ? (int) trim($_POST['id'])     : 0;
$action = isset($_POST['action']) ? trim($_POST['action'])        : '';

// Validate
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid enrollment ID']);
    exit;
}

if (!in_array($action, ['approve', 'reject'], true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

$database = new Database();
$conn     = $database->connection();

// Confirm the enrollment exists and is currently pending
$check = $conn->prepare("SELECT id FROM enrollments WHERE id = ? AND status = 'pending'");
$check->bind_param('i', $id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $check->close();
    $conn->close();
    echo json_encode(['success' => false, 'message' => 'Enrollment not found or is no longer pending']);
    exit;
}
$check->close();

// Map action → new status (soft-update only, never DELETE)
$new_status = ($action === 'approve') ? 'approved' : 'rejected';

$stmt = $conn->prepare("UPDATE enrollments SET status = ? WHERE id = ?");
$stmt->bind_param('si', $new_status, $id);

if ($stmt->execute()) {
    $label   = ($action === 'approve') ? 'approved' : 'rejected';
    $message = "Enrollment {$label} successfully.";
    $stmt->close();
    $conn->close();
    echo json_encode(['success' => true, 'message' => $message, 'new_status' => $new_status]);
} else {
    $stmt->close();
    $conn->close();
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
}