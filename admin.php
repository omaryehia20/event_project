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
  <link rel="stylesheet" href="admin.css">
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