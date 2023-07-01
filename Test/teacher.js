// teacher.js
function closeForm() {
  $('.form-popup-bg').removeClass('is-visible');
}

$(document).ready(function($) {
  /* Contact Form Interactions */
  $('#btnOpenForm').on('click', function(event) {
    event.preventDefault();
    $('.form-popup-bg').addClass('is-visible');
  });

  // Close popup when clicking x or off popup
  $('.form-popup-bg').on('click', function(event) {
    if ($(event.target).is('.form-popup-bg') || $(event.target).is('#btnCloseForm')) {
      event.preventDefault();
      $(this).removeClass('is-visible');
    }
  });
});

function closeForm2() {
  $('.form-popup-bg2').removeClass('is-visible');
}

$(document).ready(function($) {
  /* Contact Form Interactions */
  $('#btnOpenForm2').on('click', function(event) {
    event.preventDefault();
    $('.form-popup-bg2').addClass('is-visible');
  });

  // Close popup when clicking x or off popup
  $('.form-popup-bg2').on('click', function(event) {
    if ($(event.target).is('.form-popup-bg2') || $(event.target).is('#btnCloseForm2')) {
      event.preventDefault();
      $(this).removeClass('is-visible');
    }
  });
});

// Handle "Show number of rows" selection
var selectElement = document.getElementById("numRowsSelect"); // Replace with the appropriate select element ID
var tableElement = document.getElementById("teacherTable"); // Replace with the appropriate table element ID

selectElement.addEventListener("change", function() {
  var selectedValue = parseInt(selectElement.value);
  if (selectedValue > 10) {
    tableElement.classList.add("show-all-rows");
  } else {
    tableElement.classList.remove("show-all-rows");
  }
});
