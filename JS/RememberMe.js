// Function to save the email value in localStorage
function lsRememberMe() {
  var email = document.getElementById('email').value;
  localStorage.setItem('rememberedEmail', email);
}

// Function to populate the email field if "Remember Me" is checked
function populateEmailField() {
  var rememberedEmail = localStorage.getItem('rememberedEmail');
  if (rememberedEmail) {
    document.getElementById('email').value = rememberedEmail;
  }
}

// Call the populateEmailField function when the page is loaded
window.addEventListener('load', populateEmailField);
