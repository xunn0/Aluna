function loadDeleteConfirmationState() {
  var isEnabled = localStorage.getItem("deleteConfirmationEnabled") === "true";
  var deleteToggle = document.getElementById("deleteConfirmationToggle");
  if (deleteToggle) {
    deleteToggle.checked = isEnabled;
  }
}

window.addEventListener("load", loadDeleteConfirmationState);

// Function to confirm deletion
function confirmDelete() {
  return confirm("Are you sure you want to delete this record?");
}