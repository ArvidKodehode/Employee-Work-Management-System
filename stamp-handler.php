<?php
require_once "includes/config.php";
header("Content-Type: application/json");

date_default_timezone_set("Europe/Oslo"); // Adjust if needed

$employee_id = $_POST["employee_id"] ?? null;
$confirm = $_POST["confirm"] ?? null;
$action = $_POST["action"] ?? null;

if (!$employee_id) {
    echo json_encode(["success" => false, "message" => "Employee ID is required."]);
    exit;
}

// Get user by employee ID
$stmt = $db->prepare("SELECT id, first_name, last_name FROM users WHERE employee_id = ?");
$stmt->execute([$employee_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["success" => false, "message" => "Wrong Employee ID."]);
    exit;
}

$user_id = $user["id"];
$today = date("Y-m-d");
$now = date("H:i");

// Check today's timesheet
$stmt = $db->prepare("SELECT * FROM timesheet WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $today]);
$entry = $stmt->fetch(PDO::FETCH_ASSOC);

// Confirmation step: Save time
if ($confirm && $action) {
    if ($action === "checkin") {
        if ($entry) {
            echo json_encode(["success" => false, "message" => "You already checked in today."]);
            exit;
        }
        $stmt = $db->prepare("INSERT INTO timesheet (user_id, date, time_in) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $today, $now]);
    } elseif ($action === "checkout") {
        if (!$entry || $entry["time_out"]) {
            echo json_encode(["success" => false, "message" => "You are not checked in or already checked out."]);
            exit;
        }
        $stmt = $db->prepare("UPDATE timesheet SET time_out = ? WHERE id = ?");
        $stmt->execute([$now, $entry["id"]]);
    }
    echo json_encode(["success" => true]);
    exit;
}

// Step 1: Determine what action to propose
if (!$entry) {
    // No record for today = check in
    echo json_encode([
        "success" => true,
        "action" => "checkin",
        "employee_id" => $employee_id,
        "first_name" => $user["first_name"],
        "last_name" => $user["last_name"],
        "time" => $now
    ]);
    exit;
} elseif (!$entry["time_out"]) {
    // Has checked in, but not out = check out
    echo json_encode([
        "success" => true,
        "action" => "checkout",
        "employee_id" => $employee_id,
        "first_name" => $user["first_name"],
        "last_name" => $user["last_name"],
        "time" => $now
    ]);
    exit;
} else {
    // Already checked in and out
    echo json_encode(["success" => false, "message" => "You have already checked in and out today."]);
    exit;
}
?>
