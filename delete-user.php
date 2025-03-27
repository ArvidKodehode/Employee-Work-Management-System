<?php
require_once "includes/config.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}

$employee_id = $_POST["employee_id"] ?? null;

if (!$employee_id) {
    echo json_encode(["success" => false, "error" => "Missing employee ID."]);
    exit;
}

try {
    $stmt = $db->prepare("DELETE FROM users WHERE employee_id = ?");
    $stmt->execute([$employee_id]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
