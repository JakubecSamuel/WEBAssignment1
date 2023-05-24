function sortTable(columnIndex, tableId) {
    var table, rows, switching, i, x, y, shouldSwitch, sortDirection;
    table = document.getElementById(tableId);
    switching = true;
    sortDirection = table.querySelector("th:nth-child(" + (columnIndex + 1) + ")").classList.contains("sort-asc") ? "desc" : "asc";
    table.querySelectorAll("th").forEach(function(th) {
      th.classList.remove("sort-asc", "sort-desc");
    });
    table.querySelector("th:nth-child(" + (columnIndex + 1) + ")").classList.add("sort-" + sortDirection);
    while (switching) {
      switching = false;
      rows = table.rows;
      for (i = 1; i < rows.length - 1; i++) {
        shouldSwitch = false;
        x = rows[i].getElementsByTagName("td")[columnIndex];
        y = rows[i + 1].getElementsByTagName("td")[columnIndex];
        if (sortDirection === "asc") {
          if (x.textContent.toLowerCase() > y.textContent.toLowerCase()) {
            shouldSwitch = true;
            break;
          }
        } else {
          if (x.textContent.toLowerCase() < y.textContent.toLowerCase()) {
            shouldSwitch = true;
            break;
          }
        }
      }
      if (shouldSwitch) {
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
      }
    }
}

function search() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("table");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }       
    }
}
let searchInput = document.getElementById('search');
  let table = document.getElementById('tabulka2');
  let rows = table.getElementsByTagName('tr');
  searchInput.addEventListener('input', function() {
    let searchTerm = this.value.toLowerCase();
    for (let i = 1; i < rows.length; i++) {
      let cells = rows[i].getElementsByTagName('td');
      let rowText = '';
      for (let j = 0; j < cells.length; j++) {
        rowText += cells[j].textContent.toLowerCase() + ' ';
      }
      if (rowText.indexOf(searchTerm) > -1) {
        rows[i].style.display = '';
      } else {
        rows[i].style.display = 'none';
      }
    }
  });