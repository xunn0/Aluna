// Insert Form
function openStudentForm() {
  document.getElementById("studentForm").style.display = "block";
}

function closeStudentForm() {
  document.getElementById("studentForm").style.display = "none";
}

document.getElementById("btnOpenForm2").addEventListener("click", openStudentForm);

// Update Form
function openUpdateForm(studentId, existingName, existingEmail, existingPhone, existingGender, existingDOB) {
  // Set the student ID value in the hidden input field
  document.getElementById("update_student_id").value = studentId;

  // Set the existing data of the student record in the form fields
  document.getElementById("update_name").value = existingName;
  document.getElementById("update_email").value = existingEmail;
  document.getElementById("update_phone").value = existingPhone;
  document.getElementById("update_gender").value = existingGender;
  document.getElementById("update_dob").value = existingDOB;

  // Open the update form
  document.getElementById("updateStudentForm").style.display = "block";
}

function closeUpdateStudentForm() {
  // Clear the form fields
  document.getElementById("update_student_id").value = "";
  document.getElementById("update_name").value = "";
  document.getElementById("update_email").value = "";
  document.getElementById("update_phone").value = "";
  document.getElementById("update_gender").value = "";
  document.getElementById("update_dob").value = "";

  // Close the update form
  document.getElementById("updateStudentForm").style.display = "none";
}

// Function to toggle password visibility
  function togglePasswordVisibility(inputId) {
    var input = document.getElementById(inputId);
    var icon = document.getElementById(inputId + "-toggle-icon");

    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    } else {
      input.type = "password";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    }
  }
