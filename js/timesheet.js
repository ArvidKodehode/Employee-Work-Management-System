let selectedEmployee = "";
let currentDate = new Date();

// Fetch employees when page loads
window.addEventListener("DOMContentLoaded", () => {
  fetch("get-users.php")
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById("employeeSelect");
      data.users.forEach(user => {
        const option = document.createElement("option");
        option.value = user.employee_id;
        option.textContent = `${user.first_name} ${user.last_name}`;
        select.appendChild(option);
      });
      updateMonthText();
    });
});

function updateMonthText() {
  const monthNames = ["January", "February", "March", "April", "May", "June",
                      "July", "August", "September", "October", "November", "December"];
  const label = document.getElementById("currentMonth");
  label.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
}

function changeMonth(offset) {
  currentDate.setMonth(currentDate.getMonth() + offset);
  updateMonthText();
  loadTimesheet();
}

function loadTimesheet() {
  selectedEmployee = document.getElementById("employeeSelect").value;
  if (!selectedEmployee) return;

  const year = currentDate.getFullYear();
  const month = currentDate.getMonth() + 1; // JS months = 0-11

  fetch(`get-timesheet.php?employee_id=${selectedEmployee}&year=${year}&month=${month}`)
    .then(res => res.json())
    .then(data => renderTable(data));
}

function renderTable(data) {
  const container = document.getElementById("timesheetTable");
  if (!data.success || data.entries.length === 0) {
    container.innerHTML = "<p>No data available for this period.</p>";
    return;
  }

  let html = `
    <table class="user-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Clock In</th>
          <th>Clock Out</th>
          <th>Hours Worked</th>
          <th>Modified</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
  `;

  data.entries.forEach(row => {
    const hours = (row.time_in && row.time_out) ? calcHours(row.time_in, row.time_out) : "—";
    html += `
      <tr>
        <td>${row.date}</td>
        <td>${row.time_in || "-"}</td>
        <td>${row.time_out || "-"}</td>
        <td>${hours}</td>
        <td>${row.admin_modified === "yes" ? "✅" : ""}</td>
        <td><button class="btn-edit" onclick="editRow(${row.id})">✏️ Edit</button></td>
      </tr>
    `;
  });

  html += "</tbody></table>";
  container.innerHTML = html;
}

function calcHours(timeIn, timeOut) {
  const [h1, m1] = timeIn.split(":").map(Number);
  const [h2, m2] = timeOut.split(":").map(Number);
  const inMinutes = h1 * 60 + m1;
  const outMinutes = h2 * 60 + m2;
  return ((outMinutes - inMinutes) / 60).toFixed(1);
}

function editRow(id) {
  // TODO: Open edit popup and allow admin to change times
  alert("Edit timesheet ID: " + id);
}

function loadUserTimesheet() {
  const user = JSON.parse(localStorage.getItem("user"));
  if (!user) return;

  const employee_id = user.employee_id;
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth() + 1;

  fetch(`get-timesheet.php?employee_id=${employee_id}&year=${year}&month=${month}`)
    .then(res => res.json())
    .then(data => renderUserTimesheet(data));
}

function renderUserTimesheet(data) {
  const container = document.getElementById("userTimesheetTable");
  if (!data.success || data.entries.length === 0) {
    container.innerHTML = "<p>No timesheet data found for this month.</p>";
    return;
  }

  let html = `
    <table class="user-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Clock In</th>
          <th>Clock Out</th>
          <th>Hours Worked</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
  `;

  data.entries.forEach(row => {
    const hours = (row.time_in && row.time_out) ? calcHours(row.time_in, row.time_out) : "—";
    html += `
      <tr>
        <td>${row.date}</td>
        <td>${row.time_in || "-"}</td>
        <td>${row.time_out || "-"}</td>
        <td>${hours}</td>
        <td>${row.admin_modified === "yes" ? "✅" : ""}</td>
      </tr>
    `;
  });

  html += "</tbody></table>";
  container.innerHTML = html;
}
