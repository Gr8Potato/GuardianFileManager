const params = new URLSearchParams(window.location.search);
if (params.has('status')) {
    const status = params.get('status');
    const status_container = document.getElementById('status_container');
    status_container.innerHTML = '';
    const status_text = document.createTextNode(status);
    status_container.appendChild(status_text);

}
if (params.has('error')) {
    const error = params.get('error');
    const status_container = document.getElementById('status_container');
    status_container.innerHTML = '';
    const errr_text = document.createTextNode(error);
    status_container.appendChild(errr_text);

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

document.querySelectorAll('.delete-button').forEach(function (button) {
    button.addEventListener('click', function () {
        var fileName = this.getAttribute('data-filename');
        deleteFile(fileName, this);
    });
});


function deleteFile(fileName, buttonElement) {
    fetch('deletehandler', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'filename=' + encodeURIComponent(fileName)
    })
        .then(response => response.text())
        .then(text => {
            if (text.includes("File deleted")) {
                const status_container = document.getElementById('status_container');
                status_container.innerHTML = '';
                const status_text = document.createTextNode("file deleted");
                status_container.appendChild(status_text);
                // Remove the row from the table
                var row = buttonElement.closest('tr'); // Find the closest table row
                row.remove(); // Remove the row from the table
            }
        })
        .catch(error => console.error('Error:', error));
}
