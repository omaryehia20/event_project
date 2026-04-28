<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'user') {
    die("Access denied");
}

$result = $conn->query("SELECT * FROM events WHERE status='approved'");
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

<?php while ($row = $result->fetch_assoc()) { ?>
    
    <div class="card">

        <img src="images/<?php echo $row['image']; ?>" class="event-img">
        <h3 style="color: white;"><?php echo $row['title']; ?></h3>
        <p style="color: white;"><?php echo $row['description']; ?></p>
        <p style="color: white;"><b>Location:</b> <?php echo $row['location']; ?></p>
        <p style="color: white;"><b>Date:</b> <?php echo $row['date']; ?></p>
        <p class="price">$<?php echo $row['price']; ?></p>

        <div class="buy-container">
          <a href="checkout.php?event_id=<?php echo $row['id']; ?>" class="buy-btn">Buy Ticket</a>
        </div>
        
    </div>

<?php } ?>

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