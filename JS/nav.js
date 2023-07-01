$.ajax({
  url: "nav.html",
  method: "GET",
  async: true,
  success: function(data) {
    $("#nav-placeholder").fadeOut(400, function() {
      $(this).replaceWith(data).fadeIn(400);
    });

    // Remove the collapsing functionality
    let btn = document.getElementById("nav_collapse_btn");
    if (btn) {
      btn.remove();
    }
  },
  error: function(xhr, status, error) {
    console.error("Error fetching nav.html:", error);
  }
});
