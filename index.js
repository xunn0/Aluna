const express = require('express');
const app = express();
const path = require('path');

// Serve the static files from the 'Aluna' directory
app.use(express.static(path.join(__dirname, 'Aluna')));

// Route for the root URL
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'Aluna', 'Admin', 'index.php'));
});

// Start the server
const port = process.env.PORT || 8080;
app.listen(port, () => {
  console.log(`Server running on port ${port}`);
});
