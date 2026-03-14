window.$ = window.jQuery = require('jquery');
window.ClipboardJS = require('clipboard');
window.Popper = require('popper.js').default;
require('bootstrap');

import css from '../assets/css/requirements.scss';

// Export the HTML tables to a CSV file
function export2csv() {
    var content = "";
    content += "Table;Description;Value\n";

    $("#tblServer tr").each(function() {
      $this = $(this)
      content += "Server;" + $.trim($(this).find("td:eq(0)").text())
              + ";" + $(this).find("td:eq(1)").text()  + "\n";
    });
    $("#tblDatabase tr").each(function() {
      $this = $(this)
      content += "Database;" + $.trim($(this).find("td:eq(0)").text())
              + ";" + $(this).find("td:eq(1)").text()  + "\n";
    });
    $("#tblSchema tr").each(function() {
      $this = $(this)
      content += "Schema;" + $.trim($(this).find("td:eq(0)").text())
              + ";" + $(this).find("td:eq(1)").text()  + "\n";
    });

    // Build a data URI:
    uri = "data:text/csv;charset=utf-8," + encodeURIComponent(content);
    location.href = uri;
}
