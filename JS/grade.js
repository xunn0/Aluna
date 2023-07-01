function calculateGrade(marks) {
  let grade = "";
  if (marks >= 90) {
    grade = "A+";
  } else if (marks >= 80) {
    grade = "A";
  } else if (marks >= 70) {
    grade = "B+";
  } else if (marks >= 60) {
    grade = "B";
  } else if (marks >= 50) {
    grade = "C+";
  } else if (marks >= 40) {
    grade = "C";
  } else {
    grade = "F";
  }
  return grade;
}

// Auto-fill Grade based on Marks Obtained in the Add Marks form and Update Marks form
document.getElementById("marks_obtained").addEventListener("input", function() {
  const marksInput = document.getElementById("marks_obtained");
  const gradeInput = document.getElementById("grade");
  const marks = parseInt(marksInput.value, 10);
  const grade = calculateGrade(marks);
  gradeInput.value = grade;
});

function calculateAndUpdateGrade(formType) {
  const marksInput = document.getElementById(`${formType}_marks_obtained`);
  const gradeInput = document.getElementById(`${formType}_grade`);
  const marks = parseInt(marksInput.value, 10);
  const grade = calculateGrade(marks);
  gradeInput.value = grade;
}
