//to make sure that the passwords match in account creation
function check(input) {
    if (input.value != document.getElementById('password').value) {
      input.setCustomValidity('Password must be matching.');
    } else {
      // input is valid -- reset the error message
      input.setCustomValidity('');
    }
  }