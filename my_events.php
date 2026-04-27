<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// For users: get purchased tickets
if ($role == 'user') {
    $result = $conn->query("SELECT e.*, t.id as ticket_id FROM events e 
                           INNER JOIN tickets t ON e.id = t.event_id 
                           WHERE t.user_id = $user_id 
                           ORDER BY e.date DESC");
    $page_title = "My Tickets";
    $is_user = true;
} 
// For organizers: get created events
elseif ($role == 'organizer') {
    $result = $conn->query("SELECT * FROM events WHERE created_by = $user_id ORDER BY date DESC");
    $page_title = "My Events";
    $is_user = false;
} 
else {
    die("Access denied");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $page_title; ?> - Evently</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .my-tickets-container {
      max-width: 1000px;
      margin: 60px auto;
      padding: 0 20px;
    }

    .page-title {
      font-size: 32px;
      font-weight: 700;
      color: white;
      margin: 0 0 30px 0;
      text-align: center;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: rgba(255, 255, 255, 0.7);
    }

    .empty-icon {
      font-size: 64px;
      margin-bottom: 20px;
    }

    .empty-title {
      font-size: 24px;
      font-weight: 600;
      color: white;
      margin-bottom: 10px;
    }

    .empty-text {
      font-size: 16px;
      margin-bottom: 30px;
    }

    .ticket-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      padding: 0;
      margin-bottom: 20px;
      border-radius: 15px;
      overflow: hidden;
      display: flex;
      transition: all 0.3s ease;
      animation: slideUp 0.5s ease-out;
    }

    .ticket-card:hover {
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
      transform: translateY(-5px);
    }

    .ticket-image {
      width: 200px;
      height: 200px;
      object-fit: cover;
      flex-shrink: 0;
    }

    .ticket-content {
      flex: 1;
      padding: 25px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .ticket-header {
      margin-bottom: 15px;
    }

    .ticket-title {
      font-size: 22px;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0 0 8px 0;
    }

    .ticket-description {
      font-size: 14px;
      color: #666;
      margin: 0;
    }

    .ticket-details {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 15px;
    }

    .detail {
      font-size: 13px;
    }

    .detail-label {
      color: #999;
      font-weight: 500;
      display: block;
      margin-bottom: 3px;
    }

    .detail-value {
      color: #1a1a1a;
      font-weight: 600;
      font-size: 15px;
    }

    .ticket-footer {
      display: flex;
      gap: 10px;
      align-items: center;
      justify-content: space-between;
      border-top: 1px solid #e0e0e0;
      padding-top: 15px;
    }

    .ticket-status {
      display: inline-block;
      padding: 6px 12px;
      background: #e8f5e9;
      color: #1b944e;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .ticket-actions {
      display: flex;
      gap: 10px;
    }

    .ticket-btn {
      padding: 8px 16px;
      font-size: 12px;
      font-weight: 600;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .btn-download {
      background: linear-gradient(135deg, #0358b4 0%, #025fa3 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(3, 88, 180, 0.2);
    }

    .btn-download:hover {
      background: linear-gradient(135deg, #025fa3 0%, #014890 100%);
      transform: translateY(-1px);
    }

    .btn-delete {
      background: #ffebee;
      color: #c00;
      border: 1px solid #ffcccc;
    }

    .btn-delete:hover {
      background: #ffcccc;
    }

    .btn-return {
      background: #fff3e0;
      color: #f57c00;
      border: 1px solid #ffe0b2;
    }

    .btn-return:hover {
      background: #ffe0b2;
    }

    @media (max-width: 768px) {
      .ticket-card {
        flex-direction: column;
      }

      .ticket-image {
        width: 100%;
        height: 200px;
      }

      .ticket-details {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

<div class="navbar">
  <a href="dashboard.php">Home</a>
  <a href="logout.php">Logout</a>
</div>

<div class="my-tickets-container">
  <h1 class="page-title"><?php echo $page_title; ?></h1>

  <?php 
  $count = 0;
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $count++;
  ?>
    <div class="ticket-card">
      <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" class="ticket-image">
      <div class="ticket-content">
        <div class="ticket-header">
          <h2 class="ticket-title"><?php echo $row['title']; ?></h2>
          <p class="ticket-description"><?php echo substr($row['description'], 0, 100); ?>...</p>
        </div>

        <div class="ticket-details">
          <div class="detail">
            <span class="detail-label">📅 Date</span>
            <span class="detail-value"><?php echo date('M d, Y', strtotime($row['date'])); ?></span>
          </div>
          <div class="detail">
            <span class="detail-label">📍 Location</span>
            <span class="detail-value"><?php echo $row['location']; ?></span>
          </div>
          <div class="detail">
            <span class="detail-label">💲 Price</span>
            <span class="detail-value">$<?php echo $row['price']; ?></span>
          </div>
          <div class="detail">
            <span class="detail-label">✓ Status</span>
            <span class="detail-value"><?php echo ucfirst($row['status']); ?></span>
          </div>
        </div>

        <div class="ticket-footer">
          <span class="ticket-status">✓ Confirmed</span>
          <div class="ticket-actions">
            <?php if ($is_user) { ?>
              <a href="#" class="ticket-btn btn-download">Download Ticket</a>
              <a href="return_ticket.php?ticket_id=<?php echo $row['ticket_id']; ?>" class="ticket-btn btn-return" onclick="return confirm('Are you sure you want to return this ticket?')">Return Ticket</a>
            <?php } else { ?>
              <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="ticket-btn btn-delete" onclick="return confirm('Are you sure?')">Delete Event</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  <?php 
    }
  } else {
  ?>
    <div class="empty-state">
      <div class="empty-icon"><?php echo ($is_user ? '🎟️' : '📅'); ?></div>
      <div class="empty-title"><?php echo ($is_user ? 'No Tickets Yet' : 'No Events Yet'); ?></div>
      <p class="empty-text">
        <?php echo ($is_user ? 'You haven\'t purchased any tickets yet. Start exploring events!' : 'You haven\'t created any events yet. Start organizing!'); ?>
      </p>
      <a href="<?php echo ($is_user ? 'events.php' : 'create_event.html'); ?>" class="btn-primary" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #1b944e 0%, #157a3a 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
        <?php echo ($is_user ? 'Browse Events' : 'Create Event'); ?>
      </a>
    </div>
  <?php 
  }
  ?>
</div>

</body>
</html>

</body>
</html>