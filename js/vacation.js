// vacation.js – handles vacation requests and admin approval
// vacation.js – обрабатывает запросы на отпуск и одобрение админом

/**
 * Calculate weekdays (Mon–Fri) between two dates
 * Вычисляет количество будних дней (Пн–Пт) между двумя датами
 */
function calculateWeekdays(startDateStr, endDateStr) {
    const start = new Date(startDateStr);
    const end = new Date(endDateStr);
    let count = 0;
  
    while (start <= end) {
      const day = start.getDay();
      if (day !== 0 && day !== 6) count++; // 0 = Sunday, 6 = Saturday
      start.setDate(start.getDate() + 1);
    }
  
    return count;
  }
  
  /**
   * Load vacation requests for current user
   * Загружает запросы на отпуск текущего пользователя
   */
  function loadUserVacations() {
    fetch("get-vacations.php")
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById("userVacationList");
        if (!data.success || data.vacations.length === 0) {
          container.innerHTML = "<p>No vacation requests found.</p>";
          return;
        }
  
        let totalDays = 0;
        let approvedDays = 0;
  
        let html = `
          <table class="user-table">
            <thead>
              <tr>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
        `;
  
        data.vacations.forEach(v => {
          if (v.status === "approved") approvedDays += parseInt(v.days);
          totalDays += parseInt(v.days);
          html += `
            <tr>
              <td>${v.start_date}</td>
              <td>${v.end_date}</td>
              <td>${v.days}</td>
              <td>${v.status}</td>
            </tr>
          `;
        });
  
        html += `</tbody></table>`;
        html += `<p><strong>Approved days:</strong> ${approvedDays} / 21</p>`;
        container.innerHTML = html;
      });
  }
  
  /**
   * Submit vacation request (user)
   * Отправляет запрос на отпуск (пользователь)
   */
  document.getElementById("vacationForm").addEventListener("submit", async function (e) {
    e.preventDefault();
  
    const form = e.target;
    const formData = new FormData(form);
    const startDate = formData.get("start_date");
    const endDate = formData.get("end_date");
  
    // Calculate weekdays
    const days = calculateWeekdays(startDate, endDate);
    formData.append("days", days);
  
    const res = await fetch("request-vacation.php", {
      method: "POST",
      body: formData
    });
  
    const result = await res.json();
    if (result.success) {
      alert("Vacation request submitted!");
      form.reset();
      loadUserVacations();
    } else {
      alert("Error: " + result.error);
    }
  });
  
  /**
   * Load all vacation requests (admin)
   * Загружает все запросы на отпуск (админ)
   */
  function loadAdminVacations() {
    fetch("get-all-vacations.php")
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById("adminVacationList");
        if (!data.success || data.vacations.length === 0) {
          container.innerHTML = "<p>No vacation requests found.</p>";
          return;
        }
  
        let html = `
          <table class="user-table">
            <thead>
              <tr>
                <th>User</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
        `;
  
        data.vacations.forEach(v => {
          html += `
            <tr>
              <td>${v.username}</td>
              <td>${v.start_date}</td>
              <td>${v.end_date}</td>
              <td>${v.days}</td>
              <td>${v.status}</td>
              <td>
                ${v.status === "pending" ? `
                  <button onclick="approveVacation(${v.id}, 'approved')">✅</button>
                  <button onclick="approveVacation(${v.id}, 'rejected')">❌</button>
                ` : ""}
              </td>
            </tr>
          `;
        });
  
        html += "</tbody></table>";
        container.innerHTML = html;
      });
  }
  
  /**
   * Approve or reject a vacation request (admin)
   * Одобряет или отклоняет запрос на отпуск (админ)
   */
  function approveVacation(id, status) {
    fetch("approve-vacation.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, status })
    })
      .then(res => res.json())
      .then(result => {
        if (result.success) {
          alert("Updated successfully!");
          loadAdminVacations();
        } else {
          alert("Error: " + result.error);
        }
      });
  }
  
  // Auto-load correct section on page load
  document.addEventListener("DOMContentLoaded", () => {
    const user = JSON.parse(localStorage.getItem("user"));
    if (user?.role === "admin") {
      loadAdminVacations();
    } else {
      loadUserVacations();
    }
  });
  