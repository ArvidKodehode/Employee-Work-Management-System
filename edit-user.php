<?php
require_once "includes/config.php";

$id = $_GET["id"] ?? null;
if (!$id) {
    die("Missing user ID.");
}

$stmt = $db->prepare("SELECT * FROM users WHERE employee_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit User</title>
  <link rel="stylesheet" href="/css/style.css?v=1.0.0">
  <link rel="icon" href="/img/nkey.ico" type="image/x-icon">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
      background-color: #f0f8ff;
    }
    h2 { margin-top: 0; }
    label { display: block; margin-top: 1rem; }
    input, select {
      width: 100%;
      padding: 0.5rem;
      margin-top: 0.3rem;
    }
    .form-actions {
      margin-top: 1.5rem;
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
    }
    .current-image {
      margin-top: 10px;
      max-width: 120px;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <h2>Edit User</h2>
  <form id="editForm" enctype="multipart/form-data">
    <input type="hidden" name="employee_id" value="<?= htmlspecialchars($user['employee_id']) ?>">
    <input type="hidden" name="current_image" value="<?= htmlspecialchars($user['profile_image']) ?>">

    <label>Username:</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label>First Name:</label>
    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

    <label>Last Name:</label>
    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label>Role:</label>
    <select name="role">
      <option value="bruker" <?= $user["role"] === "bruker" ? "selected" : "" ?>>User</option>
      <option value="admin" <?= $user["role"] === "admin" ? "selected" : "" ?>>Admin</option>
    </select>

    <label>Access Level (1–10):</label>
    <input type="number" name="access_level" min="1" max="10" value="<?= htmlspecialchars($user['access_level']) ?>" required>

    <label>RFID Code (optional):</label>
    <input type="text" name="rfid_code" value="<?= htmlspecialchars($user['rfid_code']) ?>">

    <label>New Password (leave blank to keep current):</label>
    <input type="text" name="password" placeholder="Only if changing">

    <label>Current Profile Picture:</label><br>
    <img src="/<?= htmlspecialchars($user['profile_image']) ?>" class="current-image" alt="Profile Image"><br>

    <label>Change Profile Picture:</label>
    <input type="file" name="profile_picture" accept="image/*">

    <div class="form-actions">
      <button type="submit">Save Changes</button>
      <button type="button" onclick="window.close()">Cancel</button>
    </div>
  </form>

  <script>
    document.getElementById("editForm").addEventListener("submit", async function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      const response = await fetch("update-user.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();
      if (result.success) {
        alert("User updated successfully!");
        if (window.opener) window.opener.location.reload();
        window.close();
      } else {
        alert("Error: " + result.error);
      }
    });
  </script>
</body>
</html>
