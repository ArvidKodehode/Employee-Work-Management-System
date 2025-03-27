// Load user data into My Page
function loadMyProfile() {
  const user = JSON.parse(localStorage.getItem("user"));
  if (!user) return;

  // Fyll skjemaet med brukerdata
  document.getElementById("username").value = user.username;
  document.getElementById("employee_id").value = user.employee_id;
  document.getElementById("first_name").value = user.first_name;
  document.getElementById("last_name").value = user.last_name;
  document.getElementById("email").value = user.email;
  document.getElementById("rfid_code").value = user.rfid_code || "";

  // Sett profilbilde
  const image = user.profile_image && user.profile_image !== "uploads/default.jpg"
  ? `/${user.profile_image}`
  : "/uploads/admin.jpg";

}

// Update profile from My Page
document.getElementById("myProfileForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const user = JSON.parse(localStorage.getItem("user"));
  const form = document.getElementById("myProfileForm");
  const formData = new FormData(form);

  formData.append("employee_id", user.employee_id); // Kreves for update
  formData.append("role", user.role);               // Sikrer at rollen ikke endres

  const response = await fetch("update-user.php", {
    method: "POST",
    body: formData
  });

  const result = await response.json();
  const msg = document.getElementById("saveMessage");

  if (result.success) {
    msg.style.color = "green";
    msg.textContent = "Changes saved successfully.";

    // Oppdater localStorage med ny info (uten å be om nytt bilde)
    user.first_name = formData.get("first_name");
    user.last_name = formData.get("last_name");
    user.email = formData.get("email");
    user.rfid_code = formData.get("rfid_code");
    if (formData.get("password")) user.password = formData.get("password");
    localStorage.setItem("user", JSON.stringify(user));

    // Oppdater bildet hvis nytt bilde ble valgt
    const pictureInput = document.getElementById("profile_picture");
    if (pictureInput.files.length > 0) {
      // Reload siden for å vise nytt bilde
      setTimeout(() => location.reload(), 1000);
    }
  } else {
    msg.style.color = "red";
    msg.textContent = "Error: " + result.error;
  }
});

// Kjør når "My Page" vises
document.addEventListener("DOMContentLoaded", loadMyProfile);

// Admin: Load all users into the User Management table
function loadUsers() {
  fetch("get-users.php")
    .then(response => response.json())
    .then(data => {
      if (!data.success) {
        document.getElementById("userList").innerHTML = "<p>Could not load users.</p>";
        return;
      }
      renderUserList(data.users);
    });
}

// Render user list into the #userList container
function renderUserList(users) {
  let html = `
    <table class="user-table">
      <thead>
        <tr>
          <th>Picture</th>
          <th>Username</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>RFID</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
  `;

  users.forEach(user => {
    const image = user.profile_image && user.profile_image !== "uploads/default.jpg"
  ? `/${user.profile_image}`
  : "/uploads/admin.jpg";

    html += `
      <tr>
        <td><img src="${image}" style="width:40px; border-radius:5px;"></td>
        <td>${user.username}</td>
        <td>${user.first_name} ${user.last_name}</td>
        <td>${user.email}</td>
        <td>${user.role}</td>
        <td>${user.rfid_code || "-"}</td>
        <td>
          <button onclick="editUser(${user.employee_id})">Edit</button>
          <button onclick="deleteUser(${user.employee_id})">Delete</button>
        </td>
      </tr>
    `;
  });

  html += "</tbody></table>";
  document.getElementById("userList").innerHTML = html;
}

// Open the edit-user popup
function editUser(id) {
  window.open(`edit-user.php?id=${id}`, "EditUser", "width=600,height=700,resizable=yes,scrollbars=yes");
}

// Delete a user (with confirmation)
function deleteUser(id) {
  if (!confirm("Are you sure you want to delete this user?")) return;

  fetch("delete-user.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ employee_id: id })
  })
    .then(res => res.json())
    .then(result => {
      if (result.success) {
        alert("User deleted.");
        loadUsers();
      } else {
        alert("Error: " + result.error);
      }
    });
}

// Load when User Management panel is opened
document.addEventListener("DOMContentLoaded", () => {
  const user = JSON.parse(localStorage.getItem("user"));
  if (user && user.role === "admin") {
    loadUsers();
  }
});
