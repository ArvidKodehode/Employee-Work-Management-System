<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <title>NKEY Dashboard</title>
  <link rel="stylesheet" href="/css/style.css?v=1.0.0">
  <link rel="icon" href="/img/nkey.ico" type="image/x-icon">
</head>
<body>
  <header class="main-header">
    <div class="header-left">
      <img src="/img/logo.jpg" alt="Logo" class="logo" />
    </div>
    <div class="header-center">
      <h1 class="site-title">NKEY System</h1>
    </div>
    <div class="header-right">
      <span class="user-label" id="userLabel">Guest</span>
      <img src="/img/logo.jpg" alt="User" class="user-pic" id="userPic">
      <a href="logout.php" class="logout-btn" id="logoutBtn" style="display: none;">Log out</a>
    </div>
  </header>

  <div class="main-layout">
    <aside class="sidebar" id="sidebarMenu">
      <ul id="menuList">
        <li><a href="#" onclick="visPanel('panel-home')">🏠 Home</a></li>
        <li><a href="#" onclick="visPanel('panel-stemple')">⏱ Clock In/Out</a></li>
        <li><a href="#" onclick="visPanel('panel-minside')">👤 My Page</a></li>
        <li><a href="#" onclick="visPanel('panel-my-timesheet'); loadUserTimesheet()">📅 My Timesheet</a></li>
        <li><a href="#" onclick="visPanel('panel-faq')">📖 FAQ</a></li>
        <!-- Admin panels are only visible to admins -->
        <li class="admin-only" style="display:none"><a href="#" onclick="visPanel('panel-brukere')">👥 Users</a></li>
        <li class="admin-only" style="display:none"><a href="#" onclick="visPanel('panel-stempling')">📆 Timesheet</a></li>
        <li class="admin-only" style="display:none"><a href="#" onclick="visPanel('panel-rapporter')">📊 Reports</a></li>
        <li class="admin-only" style="display:none"><a href="#" onclick="visPanel('panel-innstillinger')">⚙️ Settings</a></li>
      </ul>
    </aside>

    <main class="main-content">
      <div id="panel-home" class="panel">
        <h2>Welcome to NKEY Dashboard</h2>
        <p id="welcomeMsg">Loading user data...</p>
      </div>

      <div id="panel-my-timesheet" class="panel" style="display:none">
  <h2>My Timesheet</h2>
  <div class="month-nav">
    <button onclick="changeUserMonth(-1)">⬅️ Previous</button>
    <span id="userMonthLabel"></span>
    <button onclick="changeUserMonth(1)">Next ➡️</button>
  </div>
  <div id="userTimesheetTable"></div>
</div>


<div id="panel-minside" class="panel" style="display:none">
  <h2>My Page</h2>
  <form id="myProfileForm" enctype="multipart/form-data">
    <div style="text-align:center">
      <img id="profilePreview" src="/img/logo.jpg" alt="Profile" style="width:120px; border-radius:10px;"><br>
    </div>

    <label>Username:</label>
    <input type="text" id="username" name="username" readonly>

    <label>Employee ID:</label>
    <input type="text" id="employee_id" name="employee_id" readonly>

    <label>First Name:</label>
    <input type="text" id="first_name" name="first_name" required>

    <label>Last Name:</label>
    <input type="text" id="last_name" name="last_name" required>

    <label>Email:</label>
    <input type="email" id="email" name="email" required>

    <label>RFID Code:</label>
    <input type="text" id="rfid_code" name="rfid_code">

    <label>New Password:</label>
    <input type="password" id="password" name="password" placeholder="Leave blank to keep current">

    <label>Change Profile Picture:</label>
    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

    <div class="form-actions">
      <button type="submit">Save Changes</button>
    </div>

    <p id="saveMessage" style="margin-top: 1rem;"></p>
  </form>
</div>



      <!-- Admin panels -->
      <div id="panel-brukere" class="panel" style="display:none">
        <h2>User Management</h2>
        <button onclick="window.open('register-user.php', 'NyBrukerVindu', 'width=600,height=700,resizable=yes,scrollbars=yes')">+ New User</button>
        <div id="userList"></div>
      </div>

      <div id="panel-stempling" class="panel" style="display:none">
  <h2>Timesheet</h2>

  <!-- Employee selector -->
  <label for="employeeSelect">Select employee:</label>
  <select id="employeeSelect" onchange="loadTimesheet()">
    <option value="">-- Choose --</option>
  </select>

  <!-- Month navigation -->
  <div style="margin-top: 1rem;">
    <button onclick="changeMonth(-1)">← Previous</button>
    <span id="currentMonth" style="margin: 0 1rem; font-weight: bold;"></span>
    <button onclick="changeMonth(1)">Next →</button>
  </div>

  <!-- Timesheet table -->
  <div id="timesheetTable" style="margin-top: 2rem;"></div>
</div>


      <div id="panel-rapporter" class="panel" style="display:none">
        <h2>Reports</h2>
        <p>Generate summaries and statistics.</p>
      </div>

      <div id="panel-innstillinger" class="panel" style="display:none">
        <h2>System Settings</h2>
        <p>Manage roles and configurations.</p>
      </div>

      
    </main>
  </div>

  <footer class="main-footer">
    <p>&copy; 2025 NKEY AS</p>
  </footer>

  <script>
    // Opens an iframe popup with the specified URL
    function openIframe(url) {
      const iframeContainer = document.getElementById("iframeContainer");
      const iframePopup = document.getElementById("iframePopup");
      iframePopup.src = url;
      iframeContainer.style.display = "block";
    }

    // Closes the iframe popup
    function closeIframe() {
      const iframeContainer = document.getElementById("iframeContainer");
      const iframePopup = document.getElementById("iframePopup");
      iframePopup.src = "";
      iframeContainer.style.display = "none";
    }
  </script>
  <script src="js/panel.js"></script>
  <script src="js/auth.js"></script>
  <script src="js/bruker.js"></script>
  <script src="js/timesheet.js"></script>
</body>
</html>
