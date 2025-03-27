<?php
header("Content-Type: application/json");

try {
  // Connect to the SQLite database
  $db = new PDO("sqlite:" . __DIR__ . "/db/users.db");
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Query to fetch user details
  $stmt = $db->query("SELECT id, username, first_name, last_name, email, role, employee_id, rfid_code FROM users");
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Return the user data as a JSON response
  echo json_encode(["success" => true, "users" => $users]);

} catch (Exception $e) {
  // Return an error message as a JSON response
  echo json_encode([
    "success" => false,
    "message" => "Database error", // Changed from "Databasefeil" to "Database error"
    "error" => $e->getMessage()
  ]);
}
