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
  <style>
    .dashboard-container {
      max-width: 1200px;
      margin: 60px auto;
      padding: 0 20px;
    }

    .dashboard-header {
      text-align: center;
      margin-bottom: 50px;
    }

    .welcome-title {
      font-size: 36px;
      font-weight: 700;
      color: white;
      margin: 0 0 10px 0;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
      letter-spacing: -0.5px;
    }

    .welcome-subtitle {
      font-size: 18px;
      color: rgba(255, 255, 255, 0.8);
      margin: 0;
      font-weight: 300;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 30px;
      margin-bottom: 50px;
    }

    .admin-actions-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      margin-bottom: 50px;
    }

    .dashboard-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border-radius: 20px;
      padding: 40px 30px;
      text-align: center;
      transition: all 0.3s ease;
      animation: slideUp 0.5s ease-out;
    }

    .dashboard-card:hover {
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
      transform: translateY(-5px);
    }

    .card-icon {
      font-size: 48px;
      margin-bottom: 20px;
      display: block;
    }

    .card-title {
      font-size: 24px;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0 0 15px 0;
    }

    .card-description {
      font-size: 14px;
      color: #666;
      margin: 0 0 25px 0;
      line-height: 1.5;
    }

    .card-button {
      display: inline-block;
      padding: 12px 24px;
      font-size: 14px;
      font-weight: 600;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
      background: linear-gradient(135deg, #0358b4 0%, #025fa3 100%);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #025fa3 0%, #014890 100%);
      transform: translateY(-2px);
    }

    .btn-success {
      background: linear-gradient(135deg, #1b944e 0%, #157a3a 100%);
    }

    .btn-success:hover {
      background: linear-gradient(135deg, #157a3a 0%, #0f5a2a 100%);
      transform: translateY(-2px);
    }

    .btn-warning {
      background: linear-gradient(135deg, #f57c00 0%, #ef6c00 100%);
    }

    .btn-warning:hover {
      background: linear-gradient(135deg, #ef6c00 0%, #e65100 100%);
      transform: translateY(-2px);
    }

    .btn-danger {
      background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
    }

    .btn-danger:hover {
      background: linear-gradient(135deg, #b71c1c 0%, #ad1457 100%);
      transform: translateY(-2px);
    }

    .stats-section {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border-radius: 20px;
      padding: 30px;
      margin-bottom: 30px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .stat-item {
      text-align: center;
    }

    .stat-number {
      font-size: 32px;
      font-weight: 700;
      color: #0358b4;
      margin: 0 0 5px 0;
    }

    .stat-label {
      font-size: 14px;
      color: #666;
      margin: 0;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      .dashboard-grid {
        grid-template-columns: 1fr;
      }

      .admin-actions-grid {
        grid-template-columns: 1fr;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

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