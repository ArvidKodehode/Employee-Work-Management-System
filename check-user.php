<?php
// === Debugging enabled ===
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

try {
    // === 1. Retrieve input data ===
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Check if input is valid
    if (!$data || !isset($data["username"]) || !isset($data["password"])) {
        echo json_encode(["success" => false, "message" => "Invalid input."]);
        exit;
    }

    $username = trim($data["username"]);
    $password = $data["password"];

    // === 2. Connect to the database ===
    $db = new PDO("sqlite:" . __DIR__ . "/db/users.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // === 3. Check if user exists ===
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user does not exist, return an error
    if (!$user) {
        echo json_encode(["success" => false, "message" => "User does not exist."]);
        exit;
    }

    // === 4. Verify password ===
    if (!password_verify($password, $user["password_hash"])) {
        echo json_encode(["success" => false, "message" => "Incorrect password."]);
        exit;
    }

    // === 5. Remove sensitive fields ===
    unset($user["password_hash"]);
    echo json_encode(["success" => true, "user" => $user]);

} catch (Exception $e) {
    // Handle any exceptions and return an error response
    echo json_encode([
        "success" => false,
        "message" => "Internal error",
        "error" => $e->getMessage()
    ]);
}
