// Function to handle the toggle event
function toggleDeleteConfirmation() {
  var deleteToggle = document.getElementById("deleteConfirmationToggle");
  var isEnabled = deleteToggle.checked;
  localStorage.setItem("deleteConfirmationEnabled", isEnabled.toString());

  var confirmationMessage = isEnabled ? "Delete confirmation is now enabled." : "Delete confirmation is now disabled.";
  alert(confirmationMessage);
}

function loadDeleteConfirmationState() {
  var isEnabled = localStorage.getItem("deleteConfirmationEnabled") === "true";
  var deleteToggle = document.getElementById("deleteConfirmationToggle");
  deleteToggle.checked = isEnabled;
}

window.addEventListener("load", loadDeleteConfirmationState);

// Check the initial state after loading
console.log("Initial delete confirmation state:", localStorage.getItem("deleteConfirmationEnabled"));

// Check the state when the toggle is changed
document.getElementById("deleteConfirmationToggle").addEventListener("change", function() {
  console.log("Updated delete confirmation state:", localStorage.getItem("deleteConfirmationEnabled"));
});
