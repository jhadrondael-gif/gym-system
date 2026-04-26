<?php
// controllers/UserController/get-attendance.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"]) || !isset($_SESSION["user_id"])) {
    header("Content-Type: application/json");
    echo json_encode(["success" => false, "message" => "Unauthorized."]);
    exit();
}

require_once(__DIR__ . "/../../db.php");

header("Content-Type: application/json");

$member_id = intval($_SESSION["user_id"]);

// Month filter — defaults to current month
$month = isset($_GET["month"]) ? intval($_GET["month"]) : intval(date("m"));
$year  = isset($_GET["year"])  ? intval($_GET["year"])  : intval(date("Y"));

// Clamp valid ranges
if ($month < 1 || $month > 12) $month = intval(date("m"));
if ($year  < 2000 || $year > 2100) $year = intval(date("Y"));

$database   = new Database();
$connection = $database->connection();

// ── 1. History for selected month ────────────────────────
$stmt = $connection->prepare("
    SELECT
        id,
        check_in,
        check_out,
        DATE_FORMAT(check_in,  '%M %d, %Y')  AS date_label,
        DATE_FORMAT(check_in,  '%h:%i %p')   AS time_in,
        DATE_FORMAT(check_out, '%h:%i %p')   AS time_out,
        DATE_FORMAT(check_in,  '%Y-%m-%d')   AS date_key,
        CASE
            WHEN check_out IS NOT NULL
            THEN ROUND(TIMESTAMPDIFF(MINUTE, check_in, check_out) / 60, 1)
            ELSE NULL
        END AS duration_hours
    FROM attendance
    WHERE member_id = ?
      AND MONTH(check_in) = ?
      AND YEAR(check_in)  = ?
    ORDER BY check_in DESC
");
$stmt->bind_param("iii", $member_id, $month, $year);
$stmt->execute();
$history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ── 2. Summary stats (all-time) ──────────────────────────
$stmt = $connection->prepare("
    SELECT
        COUNT(*) AS total_visits,
        ROUND(
            AVG(
                CASE WHEN check_out IS NOT NULL
                THEN TIMESTAMPDIFF(MINUTE, check_in, check_out) / 60
                END
            ), 1
        ) AS avg_duration_hours
    FROM attendance
    WHERE member_id = ?
");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ── 3. Current streak (consecutive days checked in up to today) ──
$stmt = $connection->prepare("
    SELECT DISTINCT DATE(check_in) AS visit_date
    FROM attendance
    WHERE member_id = ?
    ORDER BY visit_date DESC
");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$dates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$streak   = 0;
$expected = new DateTime("today");

foreach ($dates as $row) {
    $visit = new DateTime($row["visit_date"]);
    if ($visit->format("Y-m-d") === $expected->format("Y-m-d")) {
        $streak++;
        $expected->modify("-1 day");
    } else {
        break;
    }
}

// ── 4. Heatmap data — all visits in selected month ───────
// Returns a map of date => visit_count for the calendar
$stmt = $connection->prepare("
    SELECT
        DATE_FORMAT(check_in, '%Y-%m-%d') AS date_key,
        COUNT(*) AS visits
    FROM attendance
    WHERE member_id = ?
      AND MONTH(check_in) = ?
      AND YEAR(check_in)  = ?
    GROUP BY date_key
");
$stmt->bind_param("iii", $member_id, $month, $year);
$stmt->execute();
$heatmapRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$heatmap = [];
foreach ($heatmapRows as $row) {
    $heatmap[$row["date_key"]] = intval($row["visits"]);
}

// ── 5. Available months that have records (for dropdown) ─
$stmt = $connection->prepare("
    SELECT DISTINCT
        YEAR(check_in)  AS y,
        MONTH(check_in) AS m
    FROM attendance
    WHERE member_id = ?
    ORDER BY y DESC, m DESC
");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$monthsRaw = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$connection->close();

$availableMonths = array_map(fn($r) => [
    "year"  => intval($r["y"]),
    "month" => intval($r["m"]),
    "label" => DateTime::createFromFormat("!m", $r["m"])->format("F") . " " . $r["y"],
], $monthsRaw);

// ── Response ─────────────────────────────────────────────
echo json_encode([
    "success" => true,
    "data"    => [
        "history"         => $history,
        "stats"           => [
            "total_visits"      => intval($stats["total_visits"] ?? 0),
            "avg_duration_hours"=> $stats["avg_duration_hours"] ?? null,
            "streak_days"       => $streak,
        ],
        "heatmap"         => $heatmap,
        "available_months"=> $availableMonths,
        "selected"        => ["month" => $month, "year" => $year],
    ],
]);     