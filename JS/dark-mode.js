 // Check if the page is the homepage
 var isHomepage = window.location.pathname === '/';

 // Function to toggle dark mode
 function toggledarkmode() {
   var body = document.body;
   var icon = document.querySelector("#theme_switch i");

   // Toggle the dark mode class on the body
   body.classList.toggle("dark_mode");

   // Toggle the icon between sun and moon
   if (icon.classList.contains("fa-sun")) {
     icon.classList.remove("fa-sun");
     icon.classList.add("fa-moon");
     // Save the user's preference for dark mode in localStorage
     localStorage.setItem("theme", "dark");
   } else {
     icon.classList.remove("fa-moon");
     icon.classList.add("fa-sun");
     // Save the user's preference for light mode in localStorage
     localStorage.setItem("theme", "light");
   }
 }

 // Function to apply the saved theme preference
 function applyThemePreference() {
   var theme = localStorage.getItem("theme");
   var body = document.body;
   var icon = document.querySelector("#theme_switch i");

   // Apply the saved theme preference
   if (theme === "dark") {
     body.classList.add("dark_mode");
     icon.classList.remove("fa-sun");
     icon.classList.add("fa-moon");
   } else {
     body.classList.remove("dark_mode");
     icon.classList.remove("fa-moon");
     icon.classList.add("fa-sun");
   }
 }

 // Apply the theme preference when the page loads
 window.onload = function() {
   applyThemePreference();

   // If the page is the homepage, set the theme to light
   if (isHomepage) {
     var body = document.body;
     var icon = document.querySelector("#theme_switch i");
     body.classList.remove("dark_mode");
     icon.classList.remove("fa-moon");
     icon.classList.add("fa-sun");
     localStorage.setItem("theme", "light");
   }
 };