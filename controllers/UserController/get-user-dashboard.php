<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$database = new Database();
$conn = $database->connection();

// --- Logged-in member info ---
$stmt = $conn->prepare("SELECT first_name, last_name, email, gender, status FROM members WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
$stmt->close();

// --- Active membership ---
$stmt = $conn->prepare("
    SELECT type, start_date, end_date, status, fee
    FROM memberships
    WHERE member_id = ? AND status = 'active'
    ORDER BY end_date DESC LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$membership = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Days left on membership
$days_left = null;
if ($membership) {
    $today    = new DateTime();
    $end_date = new DateTime($membership['end_date']);
    $diff     = $today->diff($end_date);
    $days_left = $diff->invert ? 0 : $diff->days;
}

// --- Total check-ins (all time) ---
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM attendance WHERE member_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_checkins = (int) $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// --- Check-ins this month ---
$stmt = $conn->prepare("
    SELECT COUNT(*) AS cnt FROM attendance
    WHERE member_id = ?
    AND MONTH(check_in) = MONTH(CURDATE())
    AND YEAR(check_in)  = YEAR(CURDATE())
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$checkins_this_month = (int) $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// --- Enrolled classes (approved + pending) ---
$stmt = $conn->prepare("
    SELECT c.name, c.instructor, c.day, c.time, c.description,
           e.status AS enrollment_status, e.enrolled_at
    FROM enrollments e
    JOIN classes c ON c.id = e.class_id
    WHERE e.member_id = ? AND e.status != 'rejected'
    ORDER BY FIELD(c.day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), c.time ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$enrolled_classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// --- Recent attendance (last 7 logs) ---
$stmt = $conn->prepare("
    SELECT check_in, check_out
    FROM attendance
    WHERE member_id = ?
    ORDER BY check_in DESC LIMIT 7
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_attendance = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// --- Weekly attendance (Mon–Sun of current week) ---
$stmt = $conn->prepare("
    SELECT DAYNAME(check_in) AS day_name, COUNT(*) AS cnt
    FROM attendance
    WHERE member_id = ?
    AND check_in >= DATE(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))
    GROUP BY DAYOFWEEK(check_in), day_name
    ORDER BY DAYOFWEEK(check_in)
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$weekly = ['Monday'=>0,'Tuesday'=>0,'Wednesday'=>0,'Thursday'=>0,'Friday'=>0,'Saturday'=>0,'Sunday'=>0];
foreach ($rows as $r) { $weekly[$r['day_name']] = (int) $r['cnt']; }

$conn->close();

// --- Helpers ---
function duration($check_in, $check_out) {
    if (!$check_out) return null;
    $diff    = strtotime($check_out) - strtotime($check_in);
    $hours   = floor($diff / 3600);
    $minutes = floor(($diff % 3600) / 60);
    return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
}
?>