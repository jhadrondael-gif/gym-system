<?php
require_once(__DIR__ . "/../../db.php");

header("Content-Type: application/json");

$action = $_GET["action"] ?? $_POST["action"] ?? "";

// For POST actions, read JSON body
$input = json_decode(file_get_contents("php://input"), true);
if (!empty($input["action"])) {
    $action = $input["action"];
}

$database   = new Database();
$connection = $database->connection();

switch ($action) {

    // GET ?action=search&q=John
    case "search":
        $q = trim($_GET["q"] ?? "");

        if (strlen($q) < 2) {
            echo json_encode(["success" => false, "data" => []]);
            break;
        }

        $search = "%" . $q . "%";
        $stmt   = $connection->prepare("
            SELECT id, first_name, last_name
            FROM members
            WHERE CONCAT(first_name, ' ', last_name) LIKE ?
            ORDER BY first_name ASC
            LIMIT 10
        ");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result  = $stmt->get_result();
        $members = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode(["success" => true, "data" => $members]);
        $stmt->close();
        break;

    // GET ?action=status&member_id=5
    case "status":
        $member_id = intval($_GET["member_id"] ?? 0);

        if (!$member_id) {
            echo json_encode(["checked_in" => false, "check_in_time" => null]);
            break;
        }

        $stmt = $connection->prepare("
            SELECT id, check_in
            FROM attendance
            WHERE member_id = ?
              AND DATE(check_in) = CURDATE()
              AND check_out IS NULL
            ORDER BY check_in DESC
            LIMIT 1
        ");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();

        if ($row) {
            echo json_encode(["checked_in" => true, "check_in_time" => $row["check_in"]]);
        } else {
            echo json_encode(["checked_in" => false, "check_in_time" => null]);
        }

        $stmt->close();
        break;

    // POST { "action": "checkin", "member_id": 5 }
    case "checkin":
        $member_id = intval($input["member_id"] ?? 0);

        if (!$member_id) {
            echo json_encode(["success" => false, "message" => "Invalid member."]);
            break;
        }

        // Block duplicate check-in
        $stmt = $connection->prepare("
            SELECT id FROM attendance
            WHERE member_id = ?
              AND DATE(check_in) = CURDATE()
              AND check_out IS NULL
            LIMIT 1
        ");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "You are already checked in."]);
            $stmt->close();
            break;
        }

        $stmt->close();

        $stmt = $connection->prepare("
            INSERT INTO attendance (member_id, check_in)
            VALUES (?, NOW())
        ");
        $stmt->bind_param("i", $member_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Checked in successfully. Welcome!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to check in. Please try again."]);
        }

        $stmt->close();
        break;

    // POST { "action": "checkout", "member_id": 5 }
    case "checkout":
        $member_id = intval($input["member_id"] ?? 0);

        if (!$member_id) {
            echo json_encode(["success" => false, "message" => "Invalid member."]);
            break;
        }

        // Find open check-in for today
        $stmt = $connection->prepare("
            SELECT id FROM attendance
            WHERE member_id = ?
              AND DATE(check_in) = CURDATE()
              AND check_out IS NULL
            ORDER BY check_in DESC
            LIMIT 1
        ");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $stmt->close();

        // Guard: must be checked in first
        if (!$row) {
            echo json_encode(["success" => false, "message" => "You are not checked in. Please check in first."]);
            break;
        }

        $attendance_id = $row["id"];
        $stmt = $connection->prepare("
            UPDATE attendance
            SET check_out = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("i", $attendance_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Checked out successfully. See you next time!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to check out. Please try again."]);
        }

        $stmt->close();
        break;

    default:
        echo json_encode(["success" => false, "message" => "Invalid action."]);
        break;
}

$connection->close();