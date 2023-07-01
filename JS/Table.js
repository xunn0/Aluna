getPagination('#table-id');
$('#maxRows').trigger('change');

function getPagination(table) {
  $('#maxRows').on('change', function() {
    $('.pagination').html(''); // reset pagination div
    var trnum = 0; // reset tr counter
    var maxRows = parseInt($(this).val()); // get Max Rows from select option

    var totalRows = $(table + ' tbody tr').length; // number of rows
    $(table + ' tr:gt(0)').each(function() { // each TR in table and not the header
      trnum++; // Start Counter
      if (trnum > maxRows) { // if tr number gt maxRows
        $(this).hide(); // fade it out
      }
      if (trnum <= maxRows) {
        $(this).show();
      } // else fade in Important in case if it ..
    }); // was fade out to fade it in

    if (totalRows > maxRows) { // if tr total rows gt max rows option
      var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
      // numbers of pages
      for (var i = 1; i <= pagenum;) { // for each page append pagination li
        $('.pagination').append('<li data-page="' + i + '">\
								      <span>' + i++ + '<span class="sr-only">(current)</span></span>\
								    </li>').show();
      } // end for i
    } // end if row count > max rows
    $('.pagination li:first-child').addClass('active'); // add active class to the first li

    // SHOWING ROWS NUMBER OUT OF TOTAL DEFAULT
    showRowsCount(maxRows, 1, totalRows);
    // SHOWING ROWS NUMBER OUT OF TOTAL DEFAULT

    $('.pagination li').on('click', function(e) { // on click each page
      e.preventDefault();
      var pageNum = $(this).attr('data-page'); // get its number
      var trIndex = 0; // reset tr counter
      $('.pagination li').removeClass('active'); // remove active class from all li
      $(this).addClass('active'); // add active class to the clicked

      // SHOWING ROWS NUMBER OUT OF TOTAL
      showRowsCount(maxRows, pageNum, totalRows);
      // SHOWING ROWS NUMBER OUT OF TOTAL

      $(table + ' tr:gt(0)').each(function() { // each tr in table not the header
        trIndex++; // tr index counter
        // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
        if (trIndex > (maxRows * pageNum) || trIndex <= ((maxRows * pageNum) - maxRows)) {
          $(this).hide();
        } else {
          $(this).show();
        } // else fade in
      }); // end of for each tr in table
    }); // end of on click pagination list
  }); // end of on select change

  // END OF PAGINATION
}

// SI SETTING
$(function() {
  // Just to append id number for each row
  defaultIndex('#attendance_table', 1); // Modify the attendance table ID and starting index here
  defaultIndex('#record_table', 1); // Modify the record table ID and starting index here
  defaultIndex('#table-id', 1); // Modify the teacher, mark, student and class table ID and starting index here
});

// ROWS SHOWING FUNCTION
function showRowsCount(maxRows, pageNum, totalRows) {
  // Default rows showing
  var end_index = maxRows * pageNum;
  var start_index = ((maxRows * pageNum) - maxRows) + parseFloat(1);
  var string = 'Showing ' + start_index + ' to ' + end_index + ' of ' + totalRows + ' entries';
  $('.rows_count').html(string);
}

// CREATING INDEX
function defaultIndex(tableId, startIndex) {
  $(tableId + ' tr:eq(0)').prepend('<th style="width: 50px;"> No. </th>');

  var id = startIndex;

  $(tableId + ' tr:gt(0)').each(function() {
    $(this).prepend('<td style="width: 50px;">' + id + '</td>');
    id++;
  });
}

// All Table search script
var searchTimeout; // Declare a variable to store the search timeout

function FilterkeyWordAllTable() {
  var count = $('.table').children('tbody').children('tr:first-child').children('td').length;
  var input = document.getElementById("search_input_all");
  var input_value = input.value.toLowerCase();

  // Check if the input value is empty
  if (input_value === '') {
    // Reset the table instantly
    resetTable();
    return;
  }

  // Clear the timeout to avoid unnecessary search triggers
  clearTimeout(searchTimeout);

  // Perform the search after a brief delay
  searchTimeout = setTimeout(function() {
    var table = document.getElementById("table-id");
    var tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those that don't match the search query
    for (var i = 1; i < tr.length; i++) {
      var flag = 0;

      for (var j = 0; j < count; j++) {
        var td = tr[i].getElementsByTagName("td")[j];
        if (td) {
          var td_text = td.innerHTML;
          if (td_text.toLowerCase().indexOf(input_value) > -1) {
            flag = 1;
          }
        }
      }

      if (flag === 1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }, 300); // Adjust the timeout duration as needed
}

// Function to reset the table instantly
function resetTable() {
  $('#maxRows').trigger('change');
}

// Event listener for the search input
$('#search_input_all').on('input', function() {
  FilterkeyWordAllTable();
});

// Event listener for Ctrl+Backspace key combination
$('#search_input_all').on('keydown', function(event) {
  if (event.ctrlKey && event.code === 'Backspace') {
    event.preventDefault(); // Prevent the default behavior of Ctrl+Backspace (deleting a whole word)
    var input = $(this);
    var originalValue = input.val();

    // Clear the search input instantly
    input.val('').trigger('input');

    // Check if the search input was cleared instantly
    setTimeout(function() {
      var newValue = input.val();
      if (newValue === '' && originalValue !== '') {
        resetTable();
      }
    }, 0);
  }
});
