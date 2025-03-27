<?php
require_once "includes/config.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $username     = trim($_POST["username"] ?? "");
        $first_name   = trim($_POST["first_name"] ?? "");
        $last_name    = trim($_POST["last_name"] ?? "");
        $email        = trim($_POST["email"] ?? "");
        $role         = $_POST["role"] ?? "bruker";
        $access_level = (int)($_POST["level"] ?? 1);
        $rfid_code    = trim($_POST["rfid_code"] ?? "");
        $password     = $_POST["password"] ?? "";

        if (!$username || !$first_name || !$last_name || !$email || !$password) {
            echo json_encode(["success" => false, "error" => "All required fields must be filled."]);
            exit;
        }

        // Convert empty RFID to null
        if ($rfid_code === "") {
            $rfid_code = null;
        }

        // Check for duplicate RFID if not null
        if (!is_null($rfid_code)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE rfid_code = ?");
            $stmt->execute([$rfid_code]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(["success" => false, "error" => "RFID code is already registered to another user."]);
                exit;
            }
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Generate employee_id
        $stmt = $db->query("SELECT MAX(employee_id) as max_id FROM users");
        $maxId = $stmt->fetch(PDO::FETCH_ASSOC)["max_id"] ?? 1000;
        $employee_id = $maxId + 1;

        // Handle profile image
        $profile_image = "uploads/default.jpg";

        if (!empty($_FILES["profile_picture"]["name"])) {
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedTypes)) {
                echo json_encode(["success" => false, "error" => "Invalid file type. Only JPG, PNG, GIF, WEBP allowed."]);
                exit;
            }

            $uploadDir = __DIR__ . "/uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = "user_" . uniqid() . "." . $ext;
            $targetPath = $uploadDir . $fileName;
            $relativePath = "uploads/" . $fileName;

            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetPath)) {
                $profile_image = $relativePath;
            } else {
                echo json_encode(["success" => false, "error" => "Failed to upload profile image."]);
                exit;
            }
        }

        // Insert user
        $stmt = $db->prepare("INSERT INTO users 
            (username, first_name, last_name, email, role, access_level, rfid_code, password_hash, profile_image, employee_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $username,
            $first_name,
            $last_name,
            $email,
            $role,
            $access_level,
            $rfid_code,
            $password_hash,
            $profile_image,
            $employee_id
        ]);

        echo json_encode(["success" => true]);
        exit;

    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}
?>
