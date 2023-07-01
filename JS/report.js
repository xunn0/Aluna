function printTable() {
    // Clone the table element
    var table = document.querySelector('.table-class');
    var clonedTable = table.cloneNode(true);
  
    // Remove the actions column from the cloned table
    var actionsColumn = clonedTable.querySelector('th:nth-child(8), td:nth-child(8)');
    if (actionsColumn) {
      actionsColumn.remove();
    }
  
    // Get all the table rows, including hidden rows
    var allRows = Array.from(clonedTable.querySelectorAll('tbody tr'));
  
    // Show all the table rows
    allRows.forEach(function (row) {
      row.style.display = '';
    });
  
    // Center the contents of the table cells
    var tableCells = clonedTable.querySelectorAll('td, th');
    tableCells.forEach(function (cell) {
      cell.style.textAlign = 'center';
      cell.style.verticalAlign = 'middle';
    });
  
    // Add table lines
    clonedTable.style.borderCollapse = 'collapse';
    clonedTable.style.border = '1px solid black';
  
    var tableRows = clonedTable.querySelectorAll('tr');
    tableRows.forEach(function (row) {
      row.style.border = '1px solid black';
    });
  
    var tableCells = clonedTable.querySelectorAll('td, th');
    tableCells.forEach(function (cell) {
      cell.style.border = '1px solid black';
      cell.style.padding = '5px';
    });
  
    // Create a new window for printing
    var printWindow = window.open('', '_blank');
    printWindow.document.open();
  
    // Generate the HTML content for printing
    var htmlContent = `
      <html>
      <head>
        <title>Teachers Report</title>
        <style>
          /* Add your custom styles for printing here */
          .table-class th:nth-child(8),
          .table-class td:nth-child(8) {
            display: none;
          }
  
          /* Center the "Teachers Report" text */
          .report-heading {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
          }

        </style>
      </head>
      <body>
        <h2 class="report-heading">Teachers Report</h2>
        ${clonedTable.outerHTML}
      </body>
      </html>
    `;
  
    // Write the HTML content to the print window
    printWindow.document.write(htmlContent);
    printWindow.document.close();
  
    // Wait for the print window to finish loading the content
    printWindow.onload = function () {
      // Print the document
      printWindow.print();
      // Close the print window after printing
      printWindow.close();
    };
  }
  