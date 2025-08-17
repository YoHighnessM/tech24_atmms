document.addEventListener("DOMContentLoaded", function () {
  var toggle = document.querySelector(".dropdown-toggle");
  var menu = document.querySelector(".dropdown-menu");
  toggle.addEventListener("click", function (e) {
    e.stopPropagation();
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  });
  document.addEventListener("click", function () {
    menu.style.display = "none";
  });
});

function downloadExcel() {
  alert("Download as Excel feature coming soon!");
}

function filterTable() {
  const input = document.querySelector('input[name="search_query"]');
  const filter = input.value.toLowerCase();
  const table = document.querySelector(".table-modern");
  const tr = table.querySelectorAll("tbody tr");

  tr.forEach((row) => {
    let rowVisible = false;
    row.querySelectorAll("td").forEach((cell) => {
      if (cell.textContent.toLowerCase().includes(filter)) {
        rowVisible = true;
      }
    });
    if (rowVisible || filter === "") {
      row.style.display = "table-row";
    } else {
      row.style.display = "none";
    }
  });
}
