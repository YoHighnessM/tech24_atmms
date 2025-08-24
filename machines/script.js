document.addEventListener("DOMContentLoaded", function () {
  var toggle = document.querySelector(".dropdown-toggle");
  var menu = document.querySelector(".dropdown-menu");

  if (toggle && menu) {
    toggle.addEventListener("click", function (e) {
      e.stopPropagation();
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function () {
      menu.style.display = "none";
    });
  }
});

function downloadExcel() {
  alert("Download as Excel feature coming soon!");
}

function filterTable() {
  const input = document.querySelector('input[name="search_query"]');
  if (!input) return;

  const filter = input.value.toLowerCase();
  const table = document.querySelector(".table-modern");
  if (!table) return;

  const tr = table.querySelectorAll("tbody tr");

  tr.forEach((row) => {
    let rowVisible = false;
    row.querySelectorAll("td").forEach((cell) => {
      if (cell.textContent.toLowerCase().includes(filter)) {
        rowVisible = true;
      }
    });
    row.style.display = rowVisible || filter === "" ? "table-row" : "none";
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.querySelector('input[name="search_query"]');

  if (!searchInput) return;

  document.addEventListener("keydown", function (e) {
    // Use Alt+S as shortcut
    if (e.altKey && e.key.toLowerCase() === "s") {
      e.preventDefault();
      searchInput.focus();
    } else if (e.key === "Escape") {
      searchInput.blur();
    }
  });
});
