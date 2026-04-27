<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$result = $conn->query("SELECT * FROM events WHERE status='pending'");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel - Evently</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .admin-container {
      max-width: 1200px;
      margin: 60px auto;
      padding: 0 20px;
    }

    .admin-header {
      text-align: center;
      margin-bottom: 50px;
    }

    .admin-title {
      font-size: 36px;
      font-weight: 700;
      color: white;
      margin: 0 0 10px 0;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
      letter-spacing: -0.5px;
    }

    .admin-subtitle {
      font-size: 18px;
      color: rgba(255, 255, 255, 0.8);
      margin: 0;
      font-weight: 300;
    }

    .events-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
      gap: 30px;
      margin-bottom: 50px;
    }

    .event-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border-radius: 20px;
      padding: 30px;
      transition: all 0.3s ease;
      animation: slideUp 0.5s ease-out;
    }

    .event-card:hover {
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
      transform: translateY(-5px);
    }

    .event-title {
      font-size: 24px;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0 0 15px 0;
    }

    .event-description {
      font-size: 14px;
      color: #666;
      margin: 0 0 15px 0;
      line-height: 1.5;
    }

    .event-details {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 25px;
    }

    .event-detail {
      font-size: 14px;
      color: #555;
      margin: 0;
    }

    .event-detail strong {
      color: #1a1a1a;
    }

    .approve-btn {
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
      background: linear-gradient(135deg, #1b944e 0%, #157a3a 100%);
    }

    .approve-btn:hover {
      background: linear-gradient(135deg, #157a3a 0%, #0f5a2a 100%);
      transform: translateY(-2px);
    }

    .back-btn {
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
      background: linear-gradient(135deg, #666 0%, #555 100%);
      margin-top: 30px;
    }

    .back-btn:hover {
      background: linear-gradient(135deg, #555 0%, #444 100%);
      transform: translateY(-2px);
    }

    .no-events {
      text-align: center;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border-radius: 20px;
      padding: 60px 30px;
      margin: 50px 0;
    }

    .no-events-icon {
      font-size: 64px;
      margin-bottom: 20px;
      display: block;
    }

    .no-events-title {
      font-size: 24px;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0 0 15px 0;
    }

    .no-events-text {
      font-size: 16px;
      color: #666;
      margin: 0;
      line-height: 1.5;
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
      .events-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

<header class="header-bar">
    <img src="images/logofinal.png" alt="Evently Logo" class="logo">
</header>

<div class="admin-container">
  <div class="admin-header">
    <h1 class="admin-title">Admin Panel</h1>
    <p class="admin-subtitle">Review and approve pending events</p>
  </div>

  <?php if ($result->num_rows > 0) { ?>
    <div class="events-grid">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="event-card">
          <h3 class="event-title"><?php echo htmlspecialchars($row['title']); ?></h3>
          <p class="event-description"><?php echo htmlspecialchars($row['description']); ?></p>

          <div class="event-details">
            <p class="event-detail"><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
            <p class="event-detail"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
            <p class="event-detail"><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
            <p class="event-detail"><strong>Organizer ID:</strong> <?php echo htmlspecialchars($row['created_by']); ?></p>
          </div>

          <a class="approve-btn" href="approve.php?id=<?php echo $row['id']; ?>">
            Approve Event
          </a>
        </div>
      <?php } ?>
    </div>
  <?php } else { ?>
    <div class="no-events">
      <span class="no-events-icon">✅</span>
      <h3 class="no-events-title">All Caught Up!</h3>
      <p class="no-events-text">There are no pending events waiting for approval at the moment.</p>
    </div>
  <?php } ?>

  <div style="text-align: center;">
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
  </div>
</div>

</body>
</html>