<?php
require_once "includes/config.php";
header("Content-Type: application/json");

// Validate input
$employee_id = $_GET["employee_id"] ?? null;
$year = $_GET["year"] ?? null;
$month = $_GET["month"] ?? null;

if (!$employee_id || !$year || !$month) {
  echo json_encode(["success" => false, "message" => "Missing parameters."]);
  exit;
}

// Get internal user ID
$stmt = $db->prepare("SELECT id FROM users WHERE employee_id = ?");
$stmt->execute([$employee_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  echo json_encode(["success" => false, "message" => "User not found."]);
  exit;
}

$user_id = $user["id"];
$month = str_pad($month, 2, "0", STR_PAD_LEFT); // Ensure 01, 02, etc.

// Get all entries for selected month
$stmt = $db->prepare("
  SELECT id, date, time_in, time_out, admin_modified
  FROM timesheet
  WHERE user_id = ? AND strftime('%Y-%m', date) = ?
  ORDER BY date DESC
");
$stmt->execute([$user_id, "$year-$month"]);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return results
echo json_encode([
  "success" => true,
  "entries" => $entries
]);
?>
