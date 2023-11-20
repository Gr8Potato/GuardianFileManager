document.getElementById('upload_button').addEventListener('click', function (e) {
    uploadFile();
});

function uploadFile() {
    console.log('uploadFile function called'); // Add this line for debugging
    const uploadForm = document.getElementById('upload_form');
    let formData = new FormData(uploadForm);

    fetch('uploadhandler', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            console.log('Response data:', data); // Add this line for debugging
            document.getElementById('upload_status').innerText = data;
            // refreshFileList();
        })
        .catch(error => {
            console.error('Error:', error); // Add this line for debugging
            document.getElementById('upload_status').innerText = 'Error: ' + error;
        });
}