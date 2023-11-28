//parses url and updates status container
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

//sorts table by name (ascending)
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
        //gets response back in form of text (via php echo) and handles reporting
        .then(response => response.text())
        .then(text => {
            const status_container = document.getElementById('status_container');
            status_container.innerHTML = '';
            if (text.includes("File deleted")) {
                const status_text = document.createTextNode("file deleted");
                status_container.appendChild(status_text);
                var row = buttonElement.closest('tr');
                row.remove(); //dynamically deletes table, preventing need to refresh
            }
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.download-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var fileName = this.getAttribute('data-filename');
            initiateDownload(fileName);
        });
    });
});

function initiateDownload(fileName) {
    var isHtmlOrPhp = fileName.endsWith('.html') || fileName.endsWith('.php');
    var fileWithoutExtension = isHtmlOrPhp ? fileName.slice(0, fileName.lastIndexOf('.')) : fileName;
    var fileTypeParam = isHtmlOrPhp ? '&type=' + (fileName.endsWith('.html') ? 'html' : 'php') : '';
    var downloadUrl = 'downloadhandler?filename=' + encodeURIComponent(fileWithoutExtension) + fileTypeParam;
    window.location.href = downloadUrl;
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.preview-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const fileName = this.getAttribute('data-filename');
            createDynamicPreview(fileName);
        });
    });
});

function createDynamicPreview(fileName) {
    //setting a generic container to store images/text in
    const previewContainer = document.createElement('div');
    previewContainer.id = 'dynamicPreview';
    previewContainer.style.position = 'fixed';
    previewContainer.style.left = '0';
    previewContainer.style.top = '0';
    previewContainer.style.width = '100%';
    previewContainer.style.height = '100%';
    previewContainer.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    previewContainer.style.zIndex = '10';
    previewContainer.style.display = 'flex';
    previewContainer.style.justifyContent = 'center';
    previewContainer.style.alignItems = 'center';

    const fileExtension = fileName.split('.').pop().toLowerCase();
    let previewElement;

    switch (fileExtension) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            previewElement = new Image();
            previewElement.src = 'previewhandler?filename=' + encodeURIComponent(fileName);
            previewElement.style.maxWidth = '90%';
            previewElement.style.maxHeight = '90%';
            break;
        case 'pdf':
        case 'txt':
            previewElement = document.createElement('iframe');
            previewElement.src = 'previewhandler?filename=' + encodeURIComponent(fileName);
            previewElement.style.width = '80%';
            previewElement.style.height = '80vh';
            previewElement.style.border = '1px solid #ddd';
            previewElement.style.backgroundColor = 'white';
            previewElement.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
            previewElement.style.borderRadius = '4px';
            break;
        default:
            previewContainer.innerText = 'Unsupported file type for preview.';
            previewContainer.onclick = function () {
                document.body.removeChild(previewContainer);
            };
            document.body.appendChild(previewContainer);
            return;
    }

    previewElement.onload = function () {
        console.log('Preview loaded for file:', fileName);
    };

    previewElement.onerror = function () {
        previewContainer.innerText = 'Unable to load preview.';
        console.error('Error loading preview for file:', fileName);
    };

    previewContainer.appendChild(previewElement);

    //this is how Discord handles their previews
    previewContainer.onclick = function () {
        document.body.removeChild(previewContainer);
    };

    document.body.appendChild(previewContainer);
}