<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../db.php");

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$conn = $database->connection();

// --- Total members (excluding admins) ---
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM members WHERE role != 'admin'");
$stmt->execute();
$total_members = (int) $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// --- New members this month (via memberships start_date) ---
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT member_id) AS cnt FROM memberships
    WHERE MONTH(start_date) = MONTH(CURDATE()) AND YEAR(start_date) = YEAR(CURDATE())
");
$stmt->execute();
$new_members_this_month = (int) $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// --- Active memberships ---
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM memberships WHERE status = 'active'");
$stmt->execute();
$active_memberships = (int) $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// --- Today's attendance ---
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance WHERE DATE(check_in) = CURDATE()");
$stmt->execute();
$today_attendance = (int) $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// --- Monthly revenue ---
$stmt = $conn->prepare("
    SELECT COALESCE(SUM(CAST(fee AS DECIMAL(10,2))), 0) AS revenue FROM memberships
    WHERE MONTH(start_date) = MONTH(CURDATE()) AND YEAR(start_date) = YEAR(CURDATE())
");
$stmt->execute();
$monthly_revenue = (float) $stmt->get_result()->fetch_assoc()['revenue'];
$stmt->close();

// --- Last month revenue (for % change) ---
$stmt = $conn->prepare("
    SELECT COALESCE(SUM(CAST(fee AS DECIMAL(10,2))), 0) AS revenue FROM memberships
    WHERE MONTH(start_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
    AND YEAR(start_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
");
$stmt->execute();
$last_month_revenue = (float) $stmt->get_result()->fetch_assoc()['revenue'];
$stmt->close();

$revenue_change = null;
if ($last_month_revenue > 0) {
    $revenue_change = round((($monthly_revenue - $last_month_revenue) / $last_month_revenue) * 100, 1);
}

// --- Weekly attendance (Mon–Sun of current week) ---
$stmt = $conn->prepare("
    SELECT DAYNAME(check_in) AS day_name, COUNT(*) AS cnt
    FROM attendance
    WHERE check_in >= DATE(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))
    GROUP BY DAYOFWEEK(check_in), day_name
    ORDER BY DAYOFWEEK(check_in)
");
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$weekly = ['Monday'=>0,'Tuesday'=>0,'Wednesday'=>0,'Thursday'=>0,'Friday'=>0,'Saturday'=>0,'Sunday'=>0];
foreach ($rows as $r) { $weekly[$r['day_name']] = (int) $r['cnt']; }

// --- Membership plan breakdown ---
$stmt = $conn->prepare("
    SELECT type, COUNT(*) AS cnt,
           SUM(CAST(fee AS DECIMAL(10,2))) AS revenue
    FROM memberships
    GROUP BY type ORDER BY cnt DESC
");
$stmt->execute();
$membership_plans = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// --- Today's classes with enrollment count ---
$today_name = date('l');
$stmt = $conn->prepare("
    SELECT c.id, c.name, c.instructor, c.time,
           COUNT(e.id) AS enrolled
    FROM classes c
    LEFT JOIN enrollments e ON e.class_id = c.id AND e.status = 'approved'
    WHERE c.day = ?
    GROUP BY c.id ORDER BY c.time ASC
");
$stmt->bind_param("s", $today_name);
$stmt->execute();
$todays_classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// --- Recent members (last 5, excluding admin) ---
$stmt = $conn->prepare("
    SELECT id, first_name, last_name, status
    FROM members WHERE role != 'admin'
    ORDER BY id DESC LIMIT 5
");
$stmt->execute();
$recent_members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

foreach ($recent_members as &$m) {
    $s2 = $conn->prepare("SELECT type FROM memberships WHERE member_id = ? ORDER BY id DESC LIMIT 1");
    $s2->bind_param("i", $m['id']);
    $s2->execute();
    $row = $s2->get_result()->fetch_assoc();
    $m['membership_type'] = $row ? $row['type'] : null;
    $s2->close();
}
unset($m);

// --- Recent enrollments (last 6) ---
$stmt = $conn->prepare("
    SELECT e.status,
           CONCAT(m.first_name, ' ', m.last_name) AS member_name,
           c.name AS class_name
    FROM enrollments e
    JOIN members m ON m.id = e.member_id
    JOIN classes c ON c.id = e.class_id
    ORDER BY e.enrolled_at DESC LIMIT 6
");
$stmt->execute();
$recent_enrollments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();

// --- Helpers ---
$plan_colors = ['#ffd700','#22c55e','#3b82f6','#a855f7','#f97316','#06b6d4'];
$max_plan_count = !empty($membership_plans) ? max(array_column($membership_plans, 'cnt')) : 1;

function peso($val) {
    return '₱' . number_format((float)$val, 0);
}
function initials($first, $last) {
    return strtoupper(substr($first,0,1) . substr($last,0,1));
}
function member_badge($type) {
    if (!$type) return ['mem-default', '—'];
    $t = strtolower($type);
    if ($t === 'vip')      return ['mem-vip',      $type];
    if ($t === 'premium')  return ['mem-premium',  $type];
    if ($t === 'standard') return ['mem-standard', $type];
    if ($t === 'basic')    return ['mem-basic',     $type];
    return ['mem-default', $type];
}
?>