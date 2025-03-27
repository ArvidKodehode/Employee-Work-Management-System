<?php
require_once "includes/config.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}

try {
    $employee_id  = $_POST["employee_id"] ?? null;
    $username     = trim($_POST["username"] ?? "");
    $first_name   = trim($_POST["first_name"] ?? "");
    $last_name    = trim($_POST["last_name"] ?? "");
    $email        = trim($_POST["email"] ?? "");
    $role         = $_POST["role"] ?? "bruker";
    $access_level = (int)($_POST["access_level"] ?? 1);
    $rfid_code    = trim($_POST["rfid_code"] ?? "");
    $password     = $_POST["password"] ?? "";

    if (!$employee_id || !$username || !$first_name || !$last_name || !$email) {
        echo json_encode(["success" => false, "error" => "Missing required fields."]);
        exit;
    }

    // Convert empty RFID to null
    if ($rfid_code === "") {
        $rfid_code = null;
    }

    // Check for duplicate RFID (exclude current user)
    if (!is_null($rfid_code)) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE rfid_code = ? AND employee_id != ?");
        $stmt->execute([$rfid_code, $employee_id]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(["success" => false, "error" => "RFID code is already used by another user."]);
            exit;
        }
    }

    // Prepare password update
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_password = true;
    } else {
        $update_password = false;
    }

    // Get current image
    $stmt = $db->prepare("SELECT profile_image FROM users WHERE employee_id = ?");
    $stmt->execute([$employee_id]);
    $current_image = $stmt->fetch(PDO::FETCH_ASSOC)["profile_image"] ?? "uploads/default.jpg";

    $profile_image = $current_image;

    // Handle new image
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
            // Delete old image if it's not default
            if ($current_image && $current_image !== "uploads/default.jpg" && file_exists(__DIR__ . "/" . $current_image)) {
                unlink(__DIR__ . "/" . $current_image);
            }

            $profile_image = $relativePath;
        } else {
            echo json_encode(["success" => false, "error" => "Failed to upload new profile image."]);
            exit;
        }
    }

    // Build SQL and parameters
    $sql = "UPDATE users SET username=?, first_name=?, last_name=?, email=?, role=?, access_level=?, rfid_code=?, profile_image=?";
    $params = [$username, $first_name, $last_name, $email, $role, $access_level, $rfid_code, $profile_image];

    if ($update_password) {
        $sql .= ", password_hash=?";
        $params[] = $password_hash;
    }

    $sql .= " WHERE employee_id=?";
    $params[] = $employee_id;

    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    echo json_encode(["success" => true]);
    exit;

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
    exit;
}
?>
