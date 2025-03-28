<?php
// Returns vacation requests for the logged-in user
// Возвращает заявки на отпуск для текущего пользователя

require_once "includes/config.php";
header("Content-Type: application/json");

session_start();
if (!isset($_SESSION["username"])) {
    echo json_encode(["success" => false, "error" => "Not authenticated"]);
    exit;
}

$username = $_SESSION["username"];

try {
    $stmt = $db->prepare("SELECT * FROM vacations WHERE username = ? ORDER BY start_date DESC");
    $stmt->execute([$username]);
    $vacations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "vacations" => $vacations]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
