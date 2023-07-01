// Insert Form
function openTeacherForm() {
  document.getElementById("teacherForm").style.display = "block";
}

function closeTeacherForm() {
  document.getElementById("teacherForm").style.display = "none";
}

document.getElementById("btnOpenForm2").addEventListener("click", openTeacherForm);

// Update Form
function openUpdateForm(teacherId, existingName, existingEmail, existingPhone, existingGender, existingDOB, existingPassword) {
  // Set the teacher ID value in the hidden input field
  document.getElementById("update_teacher_id").value = teacherId;

  // Set the existing data of the teacher record in the form fields
  document.getElementById("update_name").value = existingName;
  document.getElementById("update_email").value = existingEmail;
  document.getElementById("update_phone").value = existingPhone;

  // Select the existing gender value
  var genderSelect = document.getElementById("update_gender");
  for (var i = 0; i < genderSelect.options.length; i++) {
    if (genderSelect.options[i].value === existingGender) {
      genderSelect.options[i].selected = true;
      break;
    }
  }

  document.getElementById("update_dob").value = existingDOB;
  document.getElementById("update_password").value = existingPassword || "";

  // Open the update form
  document.getElementById("updateTeacherForm").style.display = "block";
}


function closeUpdateTeacherForm() {
  // Clear the form fields
  document.getElementById("update_teacher_id").value = "";
  document.getElementById("update_name").value = "";
  document.getElementById("update_email").value = "";
  document.getElementById("update_phone").value = "";
  document.getElementById("update_gender").value = "";
  document.getElementById("update_dob").value = "";

  // Close the update form
  document.getElementById("updateTeacherForm").style.display = "none";
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






