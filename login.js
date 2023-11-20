const params = new URLSearchParams(window.location.search);
if (params.has('error')) {
    const errorMessage = params.get('error');
    const messageContainer = document.getElementById('errorMessageContainer');
    const messageText = document.createTextNode(errorMessage);
    messageContainer.appendChild(messageText);
}