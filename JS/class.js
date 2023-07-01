// Insert Form
function openClassForm() {
  document.getElementById("classForm").style.display = "block";
}

function closeClassForm() {
  document.getElementById("classForm").style.display = "none";
}

document.getElementById("btnOpenForm2").addEventListener("click", openClassForm);

// Update Form
function openUpdateForm(classId, existingName, existingTeacherId) {
  // Set the class ID value in the hidden input field
  document.getElementById("update_class_id").value = classId;

  // Set the existing data of the class record in the form fields
  document.getElementById("update_class_name").value = existingName;
  document.getElementById("update_teacher_id").value = existingTeacherId;

  // Open the update form
  document.getElementById("updateClassForm").style.display = "block";
}

function closeUpdateForm() {
  // Clear the form fields
  document.getElementById("update_class_id").value = "";
  document.getElementById("update_class_name").value = "";
  document.getElementById("update_teacher_id").value = "";

  // Close the update form
  document.getElementById("updateClassForm").style.display = "none";
}

// Form search
function filterTeachers(formType) {
  var input, filter, select, option, i, txtValue;
  if (formType === 'insert') {
    input = document.getElementById("insert_teacher_search");
    select = document.getElementById("insert_teacher_id");
  } else if (formType === 'update') {
    input = document.getElementById("update_teacher_search");
    select = document.getElementById("update_teacher_id");
  } else {
    return; // Exit the function if formType is not recognized
  }

  filter = input.value.toUpperCase();
  option = select.getElementsByTagName("option");

  // Filter the select options based on search input
  for (i = 0; i < option.length; i++) {
    txtValue = option[i].textContent || option[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      option[i].style.display = "";
    } else {
      option[i].style.display = "none";
    }
  }
}

// Corrected event listener for opening the update form
document.addEventListener("DOMContentLoaded", function () {
  var updateButtons = document.getElementsByClassName("fa-regular fa-pen-to-square");

  for (var i = 0; i < updateButtons.length; i++) {
    updateButtons[i].addEventListener("click", function () {
      var classId = this.getAttribute("data-classid");
      var className = this.getAttribute("data-classname");
      var teacherId = this.getAttribute("data-teacherid");

      openUpdateForm(classId, className, teacherId);
    });
  }
});

// Assign Student Form
function openAssignForm() {
  document.getElementById("assignForm").style.display = "block";
}

function closeAssignForm() {
  document.getElementById("assignForm").style.display = "none";
}

