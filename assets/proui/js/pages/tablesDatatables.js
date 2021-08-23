/*
 *  Document   : tablesDatatables.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Tables Datatables page
 */

var TablesDatatables = (function () {
  return {
    init: function () {
      /* Initialize Bootstrap Datatables Integration */
      App.datatables();

      /* Initialize Datatables */
      $("#example-datatable").dataTable({
        columnDefs: [{ orderable: false, targets: [1, 4] }],
        pageLength: 10,
        lengthMenu: [
          [10, 20, 30, 40, 50, -1],
          [10, 20, 30, 40, 50, "All"],
        ],
      });

      /* Add placeholder attribute to the search input */
      $(".dataTables_filter input").attr("placeholder", "Search");
    },
  };
})();
