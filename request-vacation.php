<?php
// Handles submission of vacation requests
// Обрабатывает отправку заявок на отпуск

require_once "includes/config.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}

try {
    $username   = trim($_POST["username"] ?? "");
    $start_date = $_POST["start_date"] ?? "";
    $end_date   = $_POST["end_date"] ?? "";
    $days       = (int) ($_POST["days"] ?? 0);

    if (!$username || !$start_date || !$end_date || $days <= 0) {
        echo json_encode(["success" => false, "error" => "Missing or invalid input."]);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO vacations (username, start_date, end_date, days, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$username, $start_date, $end_date, $days]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
