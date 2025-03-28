<?php
// approve-vacation.php – approves or rejects a vacation request
// approve-vacation.php – одобряет или отклоняет запрос на отпуск

require_once "includes/config.php";
header("Content-Type: application/json");

// Ensure request is POST
// Убедитесь, что запрос – POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}

// Read and decode JSON input
// Читаем и декодируем JSON-входные данные
$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? null;
$status = $data["status"] ?? null;

if (!$id || !in_array($status, ["approved", "rejected"])) {
    echo json_encode(["success" => false, "error" => "Missing or invalid data."]);
    exit;
}

try {
    // Update the vacation status in the database
    // Обновляем статус отпуска в базе данных
    $stmt = $db->prepare("UPDATE vacations SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
