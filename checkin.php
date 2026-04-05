<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Member Check-In</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #111;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .logo {
            color: #ffd700;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            color: #888;
            font-size: 13px;
            margin-bottom: 32px;
            text-align: center;
        }

        .card {
            background: #1a1a1a;
            border: 1px solid #ffd700;
            border-radius: 14px;
            padding: 32px 28px;
            width: 100%;
            max-width: 460px;
        }

        .card h5 {
            color: #ffd700;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper input {
            width: 100%;
            background: #111;
            border: 1px solid #444;
            color: #fff;
            border-radius: 8px;
            padding: 10px 14px 10px 38px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .search-wrapper input:focus {
            outline: none;
            border-color: #ffd700;
        }

        .search-wrapper .search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            width: 16px;
            height: 16px;
        }

        .dropdown-results {
            background: #111;
            border: 1px solid #ffd700;
            border-radius: 8px;
            margin-top: 6px;
            overflow: hidden;
            display: none;
        }

        .dropdown-results.show {
            display: block;
        }

        .dropdown-item-custom {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 14px;
            color: #ccc;
            transition: 0.2s;
            border-bottom: 1px solid #222;
        }

        .dropdown-item-custom:last-child {
            border-bottom: none;
        }

        .dropdown-item-custom:hover {
            background: #ffd700;
            color: #000;
        }

        .dropdown-empty {
            padding: 12px 14px;
            color: #666;
            font-size: 13px;
            text-align: center;
        }

        .member-card {
            display: none;
            background: #111;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .member-card.show {
            display: block;
        }

        .member-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #ffd700;
            color: #000;
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .member-name {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
        }

        .member-status {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }

        .member-status span {
            color: #ffd700;
            font-weight: 600;
        }

        .btn-checkin {
            width: 100%;
            background: #28a745;
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 15px;
            margin-top: 14px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-checkin:hover { background: #218838; }

        .btn-checkout {
            width: 100%;
            background: #dc3545;
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 15px;
            margin-top: 14px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-checkout:hover { background: #c82333; }

        .btn-clear {
            width: 100%;
            background: transparent;
            color: #888;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 9px;
            font-size: 13px;
            margin-top: 8px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-clear:hover {
            border-color: #ffd700;
            color: #ffd700;
        }

        .alert-box {
            display: none;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 16px;
            text-align: center;
        }

        .alert-box.success {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid #28a745;
            color: #6fcf87;
            display: block;
        }

        .alert-box.error {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid #dc3545;
            color: #f88;
            display: block;
        }

        .clock {
            text-align: center;
            color: #555;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .clock span {
            color: #ffd700;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="logo">⚡ GYM SYSTEM</div>
<div class="subtitle">Member Attendance Portal</div>

<div class="card">
    <div class="clock">
        <span id="liveClock"></span>
    </div>

    <h5><i data-lucide="calendar-check" style="width:16px;height:16px;margin-right:6px;"></i>Log Your Attendance</h5>

    <div class="alert-box" id="alertBox"></div>

    <div class="search-wrapper">
        <i data-lucide="search" class="search-icon"></i>
        <input
            type="text"
            id="searchInput"
            placeholder="Search your name..."
            autocomplete="off"
            oninput="handleSearch()"
        >
    </div>
    <div class="dropdown-results" id="dropdownResults"></div>

    <div class="member-card" id="memberCard">
        <div class="member-avatar" id="memberAvatar"></div>
        <div class="member-name" id="memberName"></div>
        <div class="member-status" id="memberStatus"></div>

        <button class="btn-checkin" id="btnCheckIn" onclick="submitLog('checkin')">
            <i data-lucide="log-in" style="width:16px;height:16px;"></i> Check In
        </button>

        <button class="btn-checkout" id="btnCheckOut" onclick="submitLog('checkout')">
            <i data-lucide="log-out" style="width:16px;height:16px;"></i> Check Out
        </button>

        <button class="btn-clear" onclick="clearSelection()">
            ✕ Choose a different member
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();

    const CONTROLLER = "./controllers/AdminController/attendance-controller.php";

    let selectedMember = null;
    let searchTimeout  = null;

    function updateClock() {
        const now = new Date();
        document.getElementById("liveClock").textContent = now.toLocaleString("en-PH", {
            weekday: "long", year: "numeric", month: "long", day: "numeric",
            hour: "2-digit", minute: "2-digit", second: "2-digit"
        });
    }
    updateClock();
    setInterval(updateClock, 1000);

    function handleSearch() {
        clearTimeout(searchTimeout);
        const q = document.getElementById("searchInput").value.trim();
        if (q.length < 2) { closeDropdown(); return; }
        searchTimeout = setTimeout(() => fetchMembers(q), 300);
    }

    function fetchMembers(q) {
        fetch(`${CONTROLLER}?action=search&q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => renderDropdown(data.data || []))
            .catch(() => renderDropdown([]));
    }

    function renderDropdown(members) {
        const dropdown = document.getElementById("dropdownResults");
        dropdown.innerHTML = "";

        if (members.length === 0) {
            dropdown.innerHTML = `<div class="dropdown-empty">No members found.</div>`;
        } else {
            members.forEach(m => {
                const div = document.createElement("div");
                div.className   = "dropdown-item-custom";
                div.textContent = m.first_name + " " + m.last_name;
                div.onclick     = () => selectMember(m);
                dropdown.appendChild(div);
            });
        }

        dropdown.classList.add("show");
    }

    function closeDropdown() {
        document.getElementById("dropdownResults").classList.remove("show");
    }

    document.addEventListener("click", function(e) {
        if (!e.target.closest(".search-wrapper") && !e.target.closest(".dropdown-results")) {
            closeDropdown();
        }
    });

    function selectMember(member) {
        selectedMember = member;
        closeDropdown();
        document.getElementById("searchInput").value       = member.first_name + " " + member.last_name;
        document.getElementById("memberAvatar").textContent = (member.first_name[0] + member.last_name[0]).toUpperCase();
        document.getElementById("memberName").textContent   = member.first_name + " " + member.last_name;

        fetch(`${CONTROLLER}?action=status&member_id=${member.id}`)
            .then(r => r.json())
            .then(data => updateMemberUI(data.checked_in, data.check_in_time))
            .catch(() => updateMemberUI(false, null));

        document.getElementById("memberCard").classList.add("show");
        lucide.createIcons();
    }

    function updateMemberUI(isCheckedIn, checkInTime) {
        const statusEl    = document.getElementById("memberStatus");
        const btnCheckIn  = document.getElementById("btnCheckIn");
        const btnCheckOut = document.getElementById("btnCheckOut");

        if (isCheckedIn) {
            const since = checkInTime
                ? "since " + new Date(checkInTime).toLocaleTimeString("en-PH", { hour: "2-digit", minute: "2-digit" })
                : "";
            statusEl.innerHTML        = `Currently <span>Checked In</span> ${since}`;
            btnCheckIn.style.display  = "none";
            btnCheckOut.style.display = "flex";
        } else {
            statusEl.innerHTML        = "Not checked in today";
            btnCheckIn.style.display  = "flex";
            btnCheckOut.style.display = "none";
        }
    }

    function submitLog(action) {
        if (!selectedMember) return;

        fetch(CONTROLLER, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action, member_id: selectedMember.id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, "success");
                const isNowCheckedIn = action === "checkin";
                updateMemberUI(isNowCheckedIn, isNowCheckedIn ? new Date().toISOString() : null);
            } else {
                showAlert(data.message || "Something went wrong.", "error");
            }
        })
        .catch(() => showAlert("Network error. Please try again.", "error"));
    }

    function clearSelection() {
        selectedMember = null;
        document.getElementById("searchInput").value = "";
        document.getElementById("memberCard").classList.remove("show");
        hideAlert();
    }

    function showAlert(msg, type) {
        const box     = document.getElementById("alertBox");
        box.textContent = msg;
        box.className   = "alert-box " + type;
        setTimeout(hideAlert, 4000);
    }

    function hideAlert() {
        document.getElementById("alertBox").className = "alert-box";
    }
</script>
</body>
</html>