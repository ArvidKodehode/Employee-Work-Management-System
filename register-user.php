<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register New User</title>
  <link rel="stylesheet" href="/css/style.css?v=1.0.0">
  <link rel="icon" href="/img/nkey.ico" type="image/x-icon">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
      background-color: #f0f8ff;
    }
    h2 {
      margin-top: 0;
    }
    label {
      display: block;
      margin-top: 1rem;
    }
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
    .form-actions button {
      padding: 0.5rem 1rem;
    }
  </style>
</head>
<body>
  <h2>Register New User</h2>
  <form id="registerForm" enctype="multipart/form-data">
    <label>Username:</label>
    <input type="text" name="username" required>

    <label>First Name:</label>
    <input type="text" name="first_name" required>

    <label>Last Name:</label>
    <input type="text" name="last_name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Role:</label>
    <select name="role">
      <option value="bruker">User</option>
      <option value="admin">Admin</option>
    </select>

    <label>Access Level (1–10):</label>
    <input type="number" name="level" min="1" max="10" required>

    <label>RFID Code (optional):</label>
    <input type="text" name="rfid_code" placeholder="Scan or enter code">

    <label>Password:</label>
    <input type="text" name="password" required>

    <label>Profile Picture (optional):</label>
    <input type="file" name="profile_picture" accept="image/*">

    <div class="form-actions">
      <button type="submit">Register</button>
      <button type="button" onclick="window.close()">Cancel</button>
    </div>
  </form>

  <script>
    document.getElementById("registerForm").addEventListener("submit", async function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      const response = await fetch("save-user.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();
      if (result.success) {
        alert("User registered!");
        if (window.opener) window.opener.location.reload();
        window.close();
      } else {
        alert("Error: " + result.error);
      }
    });
  </script>
</body>
</html>
