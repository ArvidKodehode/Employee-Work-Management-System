// panel.js – handles switching between panels in the dashboard

/**
 * Displays the panel with the given ID and hides all other panels.
 * @param {string} id - The ID of the panel to display.
 */
function visPanel(id) {
    const paneler = document.querySelectorAll(".panel");
    paneler.forEach(p => p.style.display = "none");
  
    const valgt = document.getElementById(id);
    if (valgt) valgt.style.display = "block";
  
    // 👤 Load profile when "My Page" is selected
    if (id === "panel-minside") {
      loadMyProfile(); // Kall funksjonen fra bruker.js
    }
  }
  
