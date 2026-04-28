<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.html");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Evently</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="dashboard.css">
  <link rel="stylesheet" href="footer.css">
</head>

<body>

<header class="header-bar">
    <img src="images/logofinal.png" alt="Evently Logo" class="logo">
</header>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h1 class="welcome-title">Welcome back, <?php echo ucfirst($role); ?>!</h1>
    <p class="welcome-subtitle">Manage your events and activities</p>
  </div>

  <?php if ($role == 'admin') { ?>
    <!-- Admin Stats -->
    <div class="stats-section">
      <div class="stats-grid">
        <?php
        $pending_count = $conn->query("SELECT COUNT(*) as count FROM events WHERE status='pending'")->fetch_assoc()['count'];
        $approved_count = $conn->query("SELECT COUNT(*) as count FROM events WHERE status='approved'")->fetch_assoc()['count'];
        $total_events = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
        $total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='user'")->fetch_assoc()['count'];
        ?>
        <div class="stat-item">
          <div class="stat-number"><?php echo $pending_count; ?></div>
          <div class="stat-label">Pending Events</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $approved_count; ?></div>
          <div class="stat-label">Approved Events</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $total_events; ?></div>
          <div class="stat-label">Total Events</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $total_users; ?></div>
          <div class="stat-label">Registered Users</div>
        </div>
      </div>
    </div>

    <!-- Admin Actions -->
    <div class="admin-actions-grid">
      <div class="dashboard-card">
        <span class="card-icon">📋</span>
        <h3 class="card-title">Review Events</h3>
        <p class="card-description">Approve or reject pending event submissions from organizers.</p>
        <a href="admin.php" class="card-button btn-warning">Admin Panel</a>
      </div>
      <div class="dashboard-card">
        <span class="card-icon">👥</span>
        <h3 class="card-title">Manage Users</h3>
        <p class="card-description">View and delete user accounts and organizers.</p>
        <a href="manage_users.php" class="card-button btn-primary">User Management</a>
      </div>
      <div class="dashboard-card">
        <span class="card-icon">🚪</span>
        <h3 class="card-title">Logout</h3>
        <p class="card-description">Sign out of your account securely.</p>
        <a href="logout.php" class="card-button btn-danger">Logout</a>
      </div>
    </div>

  <?php } elseif ($role == 'organizer') { ?>
    <!-- Organizer Stats -->
    <div class="stats-section">
      <div class="stats-grid">
        <?php
        $my_events = $conn->query("SELECT COUNT(*) as count FROM events WHERE created_by='$user_id'")->fetch_assoc()['count'];
        $approved_events = $conn->query("SELECT COUNT(*) as count FROM events WHERE created_by='$user_id' AND status='approved'")->fetch_assoc()['count'];
        $pending_events = $conn->query("SELECT COUNT(*) as count FROM events WHERE created_by='$user_id' AND status='pending'")->fetch_assoc()['count'];
        $total_tickets = $conn->query("SELECT COUNT(*) as count FROM tickets t INNER JOIN events e ON t.event_id = e.id WHERE e.created_by='$user_id'")->fetch_assoc()['count'];
        ?>
        <div class="stat-item">
          <div class="stat-number"><?php echo $my_events; ?></div>
          <div class="stat-label">My Events</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $approved_events; ?></div>
          <div class="stat-label">Approved</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $pending_events; ?></div>
          <div class="stat-label">Pending</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $total_tickets; ?></div>
          <div class="stat-label">Tickets Sold</div>
        </div>
      </div>
    </div>

    <!-- Organizer Actions -->
    <div class="admin-actions-grid">
      <div class="dashboard-card">
        <span class="card-icon">➕</span>
        <h3 class="card-title">Create Event</h3>
        <p class="card-description">Add a new event to the platform for approval.</p>
        <a href="create_event.html" class="card-button btn-success">Create Event</a>
      </div>
      <div class="dashboard-card">
        <span class="card-icon">📅</span>
        <h3 class="card-title">My Events</h3>
        <p class="card-description">View and manage all your created events.</p>
        <a href="my_events.php" class="card-button btn-primary">View Events</a>
      </div>
      <div class="dashboard-card">
        <span class="card-icon">🚪</span>
        <h3 class="card-title">Logout</h3>
        <p class="card-description">Sign out of your account securely.</p>
        <a href="logout.php" class="card-button btn-danger">Logout</a>
      </div>
    </div>

  <?php } elseif ($role == 'user') { ?>
    <!-- User Actions -->
    <div class="admin-actions-grid">
      <div class="dashboard-card">
        <span class="card-icon">🎟️</span>
        <h3 class="card-title">Browse Events</h3>
        <p class="card-description">Discover and purchase tickets for upcoming events.</p>
        <a href="events.php" class="card-button btn-primary">View Events</a>
      </div>
      <div class="dashboard-card">
        <span class="card-icon">📋</span>
        <h3 class="card-title">My Tickets</h3>
        <p class="card-description">View and manage your purchased tickets.</p>
        <a href="my_events.php" class="card-button btn-success">View Tickets</a>
      </div>
      <div class="dashboard-card">
        <span class="card-icon">🚪</span>
        <h3 class="card-title">Logout</h3>
        <p class="card-description">Sign out of your account securely.</p>
        <a href="logout.php" class="card-button btn-danger">Logout</a>
      </div>
    </div>
  <?php } ?>

</div>

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