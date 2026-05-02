<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'user') {
    die("Access denied");
}

$result = $conn->query("SELECT * FROM events WHERE status='approved'");
$events = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Events - Evently</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="events.css">
</head>

<body>

<div class="navbar">
  <a href="dashboard.php">Home</a>
  <a href="my_events.php">View Purchased Tickets</a>
  <a href="logout.php">Logout</a>
</div>

<h2>Available Events</h2>

<div id="events-root"></div>

<script>
  window.EVENTLY_EVENTS = <?php echo json_encode($events, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
</script>

<script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
<script type="text/babel" src="events-react.js"></script>

<footer>
  <div class="footer-container">
    <div class="footer-content">
      <!-- Apps Section -->
      <div class="footer-section">
        <h3>Get Our Apps</h3>
        <p style="margin-bottom: 15px; font-size: 12px;">Coming Soon</p>
        <div class="app-buttons">
          <a href="#" class="app-btn" onclick="return false;">
            <span class="app-btn-icon">🍎</span>
            <span>App Store</span>
          </a>
          <a href="#" class="app-btn" onclick="return false;">
            <span class="app-btn-icon">▶️</span>
            <span>Google Play</span>
          </a>
        </div>
      </div>

      <!-- Links Section -->
      <div class="footer-section">
        <h3>Links</h3>
        <div class="footer-links">
          <a href="#" class="footer-link">Terms and Conditions</a>
          <a href="#" class="footer-link">Privacy Policy</a>
          <a href="#" class="footer-link">About Us</a>
        </div>
      </div>

      <!-- Follow Us Section -->
      <div class="footer-section">
        <h3>Follow Us</h3>
        <div class="social-icons">
          <a href="#" class="social-icon" title="Facebook">f</a>
          <a href="#" class="social-icon" title="Twitter">𝕏</a>
          <a href="#" class="social-icon" title="YouTube">▶</a>
          <a href="#" class="social-icon" title="Instagram">📷</a>
        </div>
      </div>

      <!-- Get in Touch Section -->
      <div class="footer-section">
        <h3>Get in Touch</h3>
        <a href="mailto:support@evently.com" class="contact-info">
          <span>✉️</span>
          <span>support@evently.com</span>
        </a>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="copyright">
        © 2026 Evently. All rights reserved.
      </div>
      <div class="footer-logo">EVENTLY</div>
    </div>
  </div>
</footer>

</body>
</html>