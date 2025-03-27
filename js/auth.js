// auth.js – handles user data and permissions in the dashboard

const user = JSON.parse(localStorage.getItem("user"));

if (user) {
  // Display user information
  document.getElementById("userLabel").textContent = user.username;
  document.getElementById("userPic").src = user.profile_image || "/img/logo.jpg";
  document.getElementById("logoutBtn").style.display = "inline-block";
  document.getElementById("welcomeMsg").textContent = `You are logged in as ${user.username} (${user.role})`;

  // Show admin menu if the user is an admin
  if (user.role === "admin") {
    document.querySelectorAll(".admin-only").forEach(item => {
      item.style.display = "block";
    });
  }

} else {
  // Not logged in → redirect to the start page
  window.location.href = "index.html";
}
  

