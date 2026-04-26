<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    $_SESSION["error"] = "Unauthorized.";
    header("Location: ../../membership.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["error"] = "Invalid request method.";
    header("Location: ../../membership.php");
    exit();
}

$id     = trim($_POST["id"]     ?? "");
$action = trim($_POST["action"] ?? ""); // 'approve' or 'reject'

// ── Validation ──────────────────────────────────────────────
if (!$id || !is_numeric($id)) {
    $_SESSION["error"] = "Invalid membership ID.";
    header("Location: ../../membership.php");
    exit();
}

if (!in_array($action, ["approve", "reject"])) {
    $_SESSION["error"] = "Invalid action.";
    header("Location: ../../membership.php");
    exit();
}

$database   = new Database();
$connection = $database->connection();

// ── Guard: row must exist and be pending ─────────────────────
$check = $connection->prepare("
    SELECT id, type, member_id FROM memberships
    WHERE id = ?
      AND status = 'pending'
    LIMIT 1
");
$check->bind_param("i", $id);
$check->execute();
$result     = $check->get_result();
$membership = $result->fetch_assoc();
$check->close();

if (!$membership) {
    $connection->close();
    $_SESSION["error"] = "Membership not found or is no longer pending.";
    header("Location: ../../membership.php");
    exit();
}

// ── If approving, cancel any other active membership for this member ─
if ($action === "approve") {
    $deactivate = $connection->prepare("
        UPDATE memberships
        SET status = 'cancelled'
        WHERE member_id = ?
          AND status = 'active'
    ");
    $deactivate->bind_param("i", $membership["member_id"]);
    $deactivate->execute();
    $deactivate->close();
}

$new_status = $action === "approve" ? "active" : "cancelled";
$label      = htmlspecialchars($membership["type"]);

// ── Update status ────────────────────────────────────────────
$stmt = $connection->prepare("
    UPDATE memberships
    SET status = ?
    WHERE id = ?
      AND status = 'pending'
");
$stmt->bind_param("si", $new_status, $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $_SESSION["success"] = $action === "approve"
        ? "Membership #{$id} ({$label}) has been approved and set to active."
        : "Membership #{$id} ({$label}) request has been rejected.";
} else {
    $_SESSION["error"] = "Failed to update the membership. Please try again.";
}

$stmt->close();
$connection->close();

header("Location: ../../membership.php");
exit();