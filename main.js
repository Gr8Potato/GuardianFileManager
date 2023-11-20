const params = new URLSearchParams(window.location.search);
if (params.has('status')) {
    const status = params.get('status');
    const status_container = document.getElementById('status_container');
    const status_text = document.createTextNode(status);
    status_container.appendChild(status_text);
}

document.addEventListener('DOMContentLoaded', sort_by_file_name);

function sort_by_file_name() {
    const table = document.querySelector('table tbody');
    let rows = Array.from(table.rows);

    rows.sort((a, b) => {
        let nameA = a.cells[0].textContent.trim().toLowerCase();
        let nameB = b.cells[0].textContent.trim().toLowerCase();
        return nameA.localeCompare(nameB);
    });

    rows.forEach(row => table.appendChild(row));
}