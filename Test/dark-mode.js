document.addEventListener("DOMContentLoaded", function() {
    var themeToggle = document.getElementById("themeToggle");
    themeToggle.addEventListener("change", toggleTheme);
  
    function toggleTheme() {
      var body = document.body;
      if (themeToggle.checked) {
        body.classList.add("dark_mode");
      } else {
        body.classList.remove("dark_mode");
      }
    }
  });
  