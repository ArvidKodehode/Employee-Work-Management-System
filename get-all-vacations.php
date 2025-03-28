<?php
// Returns all vacation requests for admin
// Возвращает все заявки на отпуск для администратора

require_once "includes/config.php";
header("Content-Type: application/json");

session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["success" => false, "error" => "Access denied"]);
    exit;
}

try {
    $stmt = $db->query("SELECT * FROM vacations ORDER BY start_date DESC");
    $vacations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "vacations" => $vacations]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
