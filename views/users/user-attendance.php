<?php
// views/users/user-attendance.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"]) || !isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

$member_name = htmlspecialchars($_SESSION["user"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

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
            display: flex;
            flex-direction: column;
        }
        .sidebar h4 {
            text-align: center;
            padding: 20px;
            color: #ffd700;
            font-weight: 600;
            border-bottom: 1px solid #333;
            margin: 0;
        }
        .sidebar a {
            color: #ccc;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            transition: 0.3s;
            font-size: 14px;
        }
        .sidebar a svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar a:hover  { background: #ffd700; color: #000; padding-left: 25px; }
        .sidebar a.active { background: #ffd700; color: #000; }

        /* ── Content ── */
        .content {
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-header h2 {
            color: #ffd700;
            font-weight: 600;
            margin: 0;
        }

        .page-header p {
            color: #888;
            margin: 2px 0 0;
            font-size: 13px;
        }

        /* ── Month selector ── */
        .month-select {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            color: #ffd700;
            padding: 8px 14px;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            outline: none;
        }
        .month-select option { background: #1a1a1a; color: #fff; }

        /* ── Stats row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: border-color 0.2s;
        }
        .stat-card:hover { border-color: #ffd70044; }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-icon.gold   { background: #2a2000; color: #ffd700; }
        .stat-icon.green  { background: #0a2a0a; color: #22c55e; }
        .stat-icon.blue   { background: #0a1a2a; color: #60a5fa; }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        /* ── Main grid ── */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 20px;
            align-items: start;
        }

        /* ── Card ── */
        .card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 20px;
        }

        .card-title {
            color: #ffd700;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-title svg { width: 15px; height: 15px; }

        /* ── Calendar heatmap ── */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .cal-day-label {
            text-align: center;
            font-size: 10px;
            color: #555;
            font-weight: 600;
            padding-bottom: 4px;
        }

        .cal-day {
            aspect-ratio: 1;
            border-radius: 5px;
            background: #222;
            position: relative;
            transition: transform 0.15s;
            cursor: default;
        }
        .cal-day:hover { transform: scale(1.15); z-index: 1; }

        .cal-day.empty   { background: transparent; }
        .cal-day.visited { background: #ffd70044; }
        .cal-day.visited-multi { background: #ffd700aa; }
        .cal-day.today {
            outline: 2px solid #ffd700;
            outline-offset: 1px;
        }

        .cal-day .day-num {
            position: absolute;
            bottom: 3px;
            right: 4px;
            font-size: 9px;
            color: #555;
            line-height: 1;
        }
        .cal-day.visited .day-num,
        .cal-day.visited-multi .day-num { color: #ffd700; }

        .cal-legend {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            font-size: 11px;
            color: #555;
        }
        .cal-legend-box {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }

        /* ── History table ── */
        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .history-table th {
            color: #888;
            font-weight: 500;
            text-align: left;
            padding: 8px 12px;
            border-bottom: 1px solid #2a2a2a;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .history-table td {
            padding: 12px 12px;
            border-bottom: 1px solid #1f1f1f;
            color: #ccc;
            vertical-align: middle;
        }
        .history-table tr:last-child td { border-bottom: none; }
        .history-table tr:hover td { background: #1f1f1f; }

        .badge-in {
            background: #0a2a0a;
            color: #22c55e;
            border: 1px solid #22c55e33;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-out {
            background: #1a1a2a;
            color: #60a5fa;
            border: 1px solid #60a5fa33;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-open {
            background: #2a1f00;
            color: #f59e0b;
            border: 1px solid #f59e0b33;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .duration-pill {
            font-size: 12px;
            color: #888;
        }

        /* ── Empty / loading states ── */
        .state-empty {
            text-align: center;
            color: #444;
            padding: 40px 0;
            font-size: 13px;
        }
        .state-empty svg { width: 32px; height: 32px; color: #333; display: block; margin: 0 auto 10px; }

        .skeleton {
            background: linear-gradient(90deg, #1f1f1f 25%, #2a2a2a 50%, #1f1f1f 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
            border-radius: 6px;
            height: 14px;
        }
        @keyframes shimmer {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ── Tooltip ── */
        [data-tip] { position: relative; }
        [data-tip]:hover::after {
            content: attr(data-tip);
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: #000;
            color: #ffd700;
            font-size: 10px;
            padding: 3px 7px;
            border-radius: 4px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 99;
            border: 1px solid #333;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>GYM SYSTEM</h4>
    <a href="user-dashboard.php"><i data-lucide="layout-dashboard"></i> Dashboard</a>
    <a href="user-membership.php"><i data-lucide="credit-card"></i> Membership</a>
    <a href="user-classes.php"><i data-lucide="bar-chart-3"></i> Classes</a>
    <a href="user-attendance.php" class="active"><i data-lucide="calendar-check"></i> Attendance</a>
    <a href="../../controllers/Logout.php"><i data-lucide="log-out"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">

    <div class="page-header">
        <div>
            <h2>MY ATTENDANCE</h2>
            <p>Track your gym visits and progress.</p>
        </div>
        <select class="month-select" id="monthSelect" onchange="changeMonth(this.value)">
            <option value="">Loading...</option>
        </select>
    </div>

    <!-- Stats -->
    <div class="stats-row" id="statsRow">
        <!-- skeleton -->
        <div class="stat-card">
            <div class="stat-icon gold"><i data-lucide="activity"></i></div>
            <div>
                <div class="skeleton" style="width:60px;height:26px;margin-bottom:6px;"></div>
                <div class="skeleton" style="width:80px;"></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i data-lucide="clock"></i></div>
            <div>
                <div class="skeleton" style="width:60px;height:26px;margin-bottom:6px;"></div>
                <div class="skeleton" style="width:80px;"></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i data-lucide="flame"></i></div>
            <div>
                <div class="skeleton" style="width:60px;height:26px;margin-bottom:6px;"></div>
                <div class="skeleton" style="width:80px;"></div>
            </div>
        </div>
    </div>

    <!-- Main grid -->
    <div class="main-grid">

        <!-- History table -->
        <div class="card">
            <div class="card-title">
                <i data-lucide="list"></i>
                Visit History
                <span id="historyCount" style="color:#555;font-weight:400;font-size:12px;margin-left:4px;"></span>
            </div>
            <div id="historyContent">
                <div style="padding:30px 0;">
                    <div class="skeleton" style="margin-bottom:10px;"></div>
                    <div class="skeleton" style="margin-bottom:10px;width:85%;"></div>
                    <div class="skeleton" style="width:70%;"></div>
                </div>
            </div>
        </div>

        <!-- Calendar heatmap -->
        <div style="position:sticky;top:30px;">
            <div class="card">
                <div class="card-title">
                    <i data-lucide="calendar"></i>
                    <span id="calendarTitle">Calendar</span>
                </div>
                <div id="calendarContent">
                    <div class="skeleton" style="height:160px;border-radius:8px;"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
lucide.createIcons();

const DAY_LABELS = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

let currentMonth = null;
let currentYear  = null;

// ── Boot ──────────────────────────────────────────────────
fetchAttendance(null, null);

// ── Fetch ─────────────────────────────────────────────────
function fetchAttendance(month, year) {
    let url = "../../controllers/UserController/get-attendance.php";
    if (month && year) url += `?month=${month}&year=${year}`;

    fetch(url)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;

            const d = res.data;
            currentMonth = d.selected.month;
            currentYear  = d.selected.year;

            populateMonthDropdown(d.available_months, d.selected);
            renderStats(d.stats);
            renderHistory(d.history);
            renderCalendar(d.heatmap, d.selected.month, d.selected.year);
        })
        .catch(err => {
            document.getElementById("historyContent").innerHTML =
                `<div class="state-empty"><i data-lucide="wifi-off"></i>Failed to load attendance.</div>`;
            lucide.createIcons();
        });
}

// ── Month dropdown ────────────────────────────────────────
function populateMonthDropdown(months, selected) {
    const sel = document.getElementById("monthSelect");

    // Add current month if not in list (no records yet)
    const currentKey = `${selected.year}-${selected.month}`;
    const exists = months.some(m => m.year === selected.year && m.month === selected.month);
    const allMonths = exists ? months : [
        { year: selected.year, month: selected.month, label: monthName(selected.month) + " " + selected.year },
        ...months
    ];

    sel.innerHTML = allMonths.map(m => {
        const val      = `${m.month}-${m.year}`;
        const isSelected = m.month === selected.month && m.year === selected.year;
        return `<option value="${val}" ${isSelected ? "selected" : ""}>${m.label}</option>`;
    }).join("");
}

function changeMonth(val) {
    if (!val) return;
    const [m, y] = val.split("-").map(Number);
    fetchAttendance(m, y);
}

// ── Stats ─────────────────────────────────────────────────
function renderStats(stats) {
    const avgText = stats.avg_duration_hours !== null
        ? `${stats.avg_duration_hours}h avg`
        : "—";

    document.getElementById("statsRow").innerHTML = `
        <div class="stat-card">
            <div class="stat-icon gold"><i data-lucide="activity"></i></div>
            <div>
                <div class="stat-value">${stats.total_visits}</div>
                <div class="stat-label">Total Visits (all-time)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i data-lucide="clock"></i></div>
            <div>
                <div class="stat-value">${avgText}</div>
                <div class="stat-label">Avg. Session Duration</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i data-lucide="flame"></i></div>
            <div>
                <div class="stat-value">${stats.streak_days}</div>
                <div class="stat-label">Day Streak 🔥</div>
            </div>
        </div>
    `;
    lucide.createIcons();
}

// ── History table ─────────────────────────────────────────
function renderHistory(history) {
    const container = document.getElementById("historyContent");
    const countEl   = document.getElementById("historyCount");

    countEl.textContent = history.length ? `(${history.length})` : "";

    if (history.length === 0) {
        container.innerHTML = `
            <div class="state-empty">
                <i data-lucide="calendar-x"></i>
                No visits recorded for this month.
            </div>`;
        lucide.createIcons();
        return;
    }

    let rows = history.map(r => {
        const timeOut = r.time_out
            ? `<span class="badge-out">${escHtml(r.time_out)}</span>`
            : `<span class="badge-open">Still in</span>`;

        const duration = r.duration_hours !== null
            ? `<span class="duration-pill">${r.duration_hours}h</span>`
            : `<span class="duration-pill" style="color:#555;">—</span>`;

        return `
            <tr>
                <td style="color:#fff;font-weight:500;">${escHtml(r.date_label)}</td>
                <td><span class="badge-in">${escHtml(r.time_in)}</span></td>
                <td>${timeOut}</td>
                <td>${duration}</td>
            </tr>`;
    }).join("");

    container.innerHTML = `
        <div style="overflow-x:auto;">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>`;
}

// ── Calendar heatmap ──────────────────────────────────────
function renderCalendar(heatmap, month, year) {
    const title = document.getElementById("calendarTitle");
    title.textContent = `${monthName(month)} ${year}`;

    const firstDay  = new Date(year, month - 1, 1).getDay(); // 0=Sun
    const daysInMonth = new Date(year, month, 0).getDate();
    const todayStr  = new Date().toISOString().slice(0, 10);

    let html = `<div class="calendar-grid">`;

    // Day labels
    DAY_LABELS.forEach(d => {
        html += `<div class="cal-day-label">${d}</div>`;
    });

    // Empty cells before first day
    for (let i = 0; i < firstDay; i++) {
        html += `<div class="cal-day empty"></div>`;
    }

    // Day cells
    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey = `${year}-${String(month).padStart(2,"0")}-${String(day).padStart(2,"0")}`;
        const visits  = heatmap[dateKey] || 0;
        const isToday = dateKey === todayStr;

        let cls = "cal-day";
        if (visits === 1)    cls += " visited";
        if (visits > 1)      cls += " visited-multi";
        if (isToday)         cls += " today";

        const tip = visits > 0
            ? `data-tip="${visits} visit${visits > 1 ? "s" : ""}"`
            : (isToday ? `data-tip="Today"` : "");

        html += `<div class="${cls}" ${tip}><span class="day-num">${day}</span></div>`;
    }

    html += `</div>`;

    // Legend
    html += `
        <div class="cal-legend">
            <div class="cal-legend-box" style="background:#222;"></div> No visit
            <div class="cal-legend-box" style="background:#ffd70044;margin-left:8px;"></div> 1 visit
            <div class="cal-legend-box" style="background:#ffd700aa;margin-left:8px;"></div> 2+ visits
        </div>`;

    document.getElementById("calendarContent").innerHTML = html;
}

// ── Helpers ───────────────────────────────────────────────
function monthName(m) {
    return new Date(2000, m - 1, 1).toLocaleString("default", { month: "long" });
}

function escHtml(str) {
    return String(str ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
}
</script>
</body>
</html>