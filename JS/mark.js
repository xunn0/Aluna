// Add Form
function openMarksForm() {
  document.getElementById("marksForm").style.display = "block";
}

function closeMarksForm() {
  document.getElementById("marksForm").style.display = "none";
}

document.getElementById("btnOpenForm2").addEventListener("click", openMarksForm);

document.getElementById("insert_class_id").addEventListener("change", function () {
  updateData('insert');
});

document.getElementById("insert_teacher_id").addEventListener("change", function () {
  updateData('insert');
});

function updateData(formType) {
  const classSelect = document.getElementById(formType + '_class_id');
  const teacherSelect = document.getElementById(formType + '_teacher_id');
  const studentSelect = document.getElementById(formType + '_student_id');

  // Get the selected class ID
  const selectedClassID = classSelect.value;

  // Clear the teacher and student options
  teacherSelect.innerHTML = '';
  studentSelect.innerHTML = '';

  if (selectedClassID === '') {
    // If no class is selected, return
    return;
  }

  // Make an AJAX request to fetch the teachers for the selected class
  const xhrTeachers = new XMLHttpRequest();
  xhrTeachers.open('GET', 'fetch-teachers.php?class_id=' + selectedClassID, true);
  xhrTeachers.onreadystatechange = function () {
    if (xhrTeachers.readyState === XMLHttpRequest.DONE) {
      if (xhrTeachers.status === 200) {
        const teachers = JSON.parse(xhrTeachers.responseText);

        if (teachers.length > 0) {
          // Populate the teacher options
          for (let i = 0; i < teachers.length; i++) {
            const teacherOption = document.createElement('option');
            teacherOption.value = teachers[i].teacher_id;
            teacherOption.textContent = teachers[i].name + ' | ID: ' + teachers[i].teacher_id;
            teacherSelect.appendChild(teacherOption);
          }
        } else {
          // If no teachers found, display a message
          const noTeacherOption = document.createElement('option');
          noTeacherOption.value = '';
          noTeacherOption.textContent = 'No teachers found';
          teacherSelect.appendChild(noTeacherOption);
        }
      } else {
        // Display an error message if the request fails
        const errorOption = document.createElement('option');
        errorOption.value = '';
        errorOption.textContent = 'Error fetching teachers';
        teacherSelect.appendChild(errorOption);
      }
    }
  };
  xhrTeachers.send();

  // Make an AJAX request to fetch the students for the selected class
  const xhrStudents = new XMLHttpRequest();
  xhrStudents.open('GET', 'fetch-students.php?class_id=' + selectedClassID, true);
  xhrStudents.onreadystatechange = function () {
    if (xhrStudents.readyState === XMLHttpRequest.DONE) {
      if (xhrStudents.status === 200) {
        const students = JSON.parse(xhrStudents.responseText);

        if (students.length > 0) {
          // Populate the student options
          for (let i = 0; i < students.length; i++) {
            const studentOption = document.createElement('option');
            studentOption.value = students[i].student_id;
            studentOption.textContent = students[i].name + ' | ID: ' + students[i].student_id;
            studentSelect.appendChild(studentOption);
          }
        } else {
          // If no students found, display a message
          const noStudentOption = document.createElement('option');
          noStudentOption.value = '';
          noStudentOption.textContent = 'No students found';
          studentSelect.appendChild(noStudentOption);
        }
      } else {
        // Display an error message if the request fails
        const errorOption = document.createElement('option');
        errorOption.value = '';
        errorOption.textContent = 'Error fetching students';
        studentSelect.appendChild(errorOption);
      }
    }
  };
  xhrStudents.send();
}

// Update Form
document.getElementById("btnOpenUpdateForm").addEventListener("click", function () {
  openUpdateForm(
    document.getElementById("mark_id").value,
    document.getElementById("update_test_name").value,
    document.getElementById("update_marks_obtained").value,
    document.getElementById("update_student_id").value,
    document.getElementById("update_class_id").value,
    document.getElementById("update_teacher_id").value
  );
});

document.getElementById("update_class_id").addEventListener("change", function () {
  updateData('update');
});

document.getElementById("update_teacher_id").addEventListener("change", function () {
  updateData('update');
});

document.getElementById("btnCloseUpdateForm").addEventListener("click", closeUpdateForm);

// JavaScript for updating the marks form with pre-filled values
function openUpdateForm(markID, testName, marksObtained, studentID, classID, teacherID) {
  // Populate the update form fields with the provided values
  document.getElementById('update_mark_id').value = markID;
  document.getElementById('update_test_name').value = testName;
  document.getElementById('update_marks_obtained').value = marksObtained;

  // Set the selected class in the update form
  const classSelect = document.getElementById('update_class_id');
  classSelect.value = classID;

  // Call the updateData function to populate the teacher and student options
  updateData('update');

  // Set the selected teacher and student in the update form
  document.getElementById('update_teacher_id').value = teacherID;
  document.getElementById('update_student_id').value = studentID;

  // Add event listener to class select element to update teacher and student options
  classSelect.addEventListener('change', function () {
    updateData('update');
  });

  // Open the update form
  document.getElementById('updateMarksForm').style.display = 'block';
}


function closeUpdateForm() {
  document.getElementById("updateMarksForm").style.display = "none";
}

// Function to update the test_name field based on the selected class
function updateTestName(formType) {
  var classSelect = document.getElementById(formType + "_class_id");
  var testNameInput = document.getElementById(formType + "_test_name");

  // Get the selected class name
  var selectedOption = classSelect.options[classSelect.selectedIndex];
  var className = selectedOption.text;

  // Append "Paper 1" to the class name
  var updatedTestName = className + " - Paper 1";

  // Set the test_name field value to the updated class name
  testNameInput.value = updatedTestName;
}

// Call the updateTestName function when the class selection changes
document.getElementById("insert_class_id").addEventListener("change", function () {
  updateTestName("insert");
});

document.getElementById("update_class_id").addEventListener("change", function () {
  updateTestName("update");
});





