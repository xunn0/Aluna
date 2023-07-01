<html>

<head>
  <title>Aluna - Settings</title>
  <link rel="stylesheet" type="text/css" href="../css/nav.css">
  <link rel="stylesheet" type="text/css" href="../css/settings.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
  <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
  <!-- MultiNav -->
  <script src="//code.jquery.com/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10" type="text/javascript"></script>
  <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>


</head>

<body>
  <!-- Nav -->
  <div id="nav-placeholder"></div>

  <!-- Content -->
  <div class="settings-card">
  <h2>Settings</h2>
  <div class="delete-toggle">
    <input type="checkbox" id="deleteConfirmationToggle" onchange="toggleDeleteConfirmation()">
    <label for="deleteConfirmationToggle">Enable Delete Confirmation</label>
  </div>
  <!-- Add more settings here -->
</div>

  <!-- darkmode -->
  <main>
    <header>
      <div>
        <a id="theme_switch">
          <i onclick="toggledarkmode()" class='fa-solid fa-sun'></i>
        </a>
      </div>
    </header>
  </main>

  <!-- nav.js -->
  <script src="../JS/nav.js"></script>
  <!-- dark-mode.js -->
  <script src="../JS/dark-mode.js"></script>
  <!-- settings.js -->
  <script src="../JS/settings.js"></script>

</body>

</html>