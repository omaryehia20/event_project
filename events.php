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
  <style>
    footer {
      background: rgba(20, 30, 45, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(12px);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: white;
      padding: 60px 20px 40px;
      margin-top: 80px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-section h3 {
      font-size: 18px;
      font-weight: 700;
      margin: 0 0 20px 0;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #fff;
    }

    .footer-section p {
      font-size: 14px;
      color: rgba(255, 255, 255, 0.8);
      line-height: 1.6;
      margin: 0;
    }

    .app-buttons {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .app-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 8px;
      color: white;
      text-decoration: none;
      font-size: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .app-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.5);
      transform: translateY(-2px);
    }

    .app-btn-icon {
      font-size: 18px;
    }

    .footer-links {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .footer-link {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .footer-link:hover {
      color: #fff;
      padding-left: 4px;
    }

    .social-icons {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
    }

    .social-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      color: white;
      text-decoration: none;
      font-size: 18px;
      transition: all 0.3s ease;
    }

    .social-icon:hover {
      background: rgba(3, 88, 180, 0.8);
      border-color: rgba(3, 88, 180, 0.8);
      transform: translateY(-3px);
    }

    .contact-info {
      display: flex;
      align-items: center;
      gap: 10px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .contact-info:hover {
      color: white;
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
    }

    .copyright {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.6);
    }

    .footer-logo {
      font-size: 20px;
      font-weight: 700;
      background: linear-gradient(135deg, #0358b4 0%, #025fa3 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    @media (max-width: 768px) {
      footer {
        padding: 40px 20px 30px;
        margin-top: 60px;
      }

      .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      .footer-bottom {
        flex-direction: column;
        text-align: center;
      }

      .app-buttons {
        justify-content: center;
      }

      .social-icons {
        justify-content: center;
      }
    }
  </style>
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