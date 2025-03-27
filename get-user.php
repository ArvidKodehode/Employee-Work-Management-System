<?php
header("Content-Type: application/json");

try {
  $data = json_decode(file_get_contents("php://input"), true);
  $username = $data["username"] ?? "";

  if (!$username) {
    echo json_encode(["success" => false, "message" => "Username is required"]);
    exit;
  }

  $db = new PDO("sqlite:" . __DIR__ . "/db/users.db");
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    echo json_encode(["success" => true, "user" => $user]);
  } else {
    echo json_encode(["success" => false, "message" => "User not found"]);
  }

} catch (Exception $e) {
  echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
