function validateForm() {
    var emailInput = document.forms["myForm"]["email"];
    var passwordInput = document.forms["myForm"]["password"];
  
    var email = emailInput.value.trim();
    var password = passwordInput.value.trim();
  
    if (email === "") {
      alert("Please fill out the Email field.");
      emailInput.focus();
      return false;
    }
  
    if (password === "") {
      alert("Please fill out the Password field.");
      passwordInput.focus();
      return false;
    }
  
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert("Please enter a valid email address");
      emailInput.focus();
      return false;
    }
  
    return true;
  }
  