<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Check user role
if ($_SESSION['role'] != 'user') {
    die("Access denied");
}

// Check if return data exists
if (!isset($_SESSION['return_event_title'])) {
    header("Location: my_events.php");
    exit();
}

$event_title = $_SESSION['return_event_title'];
$refund_amount = $_SESSION['return_amount'];

// Clear session variables
unset($_SESSION['return_event_title']);
unset($_SESSION['return_amount']);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ticket Returned - Evently</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .return-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: calc(100vh - 150px);
      padding: 20px;
    }

    .return-wrapper {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      padding: 50px 40px;
      border-radius: 20px;
      text-align: center;
      max-width: 500px;
      width: 100%;
      animation: slideUp 0.5s ease-out;
    }

    .return-icon {
      font-size: 80px;
      margin-bottom: 20px;
      animation: scaleIn 0.6s ease-out;
    }

    @keyframes scaleIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    .return-title {
      font-size: 32px;
      font-weight: 700;
      color: #f57c00;
      margin: 0 0 10px 0;
      letter-spacing: -0.5px;
    }

    .return-subtitle {
      font-size: 16px;
      color: #666;
      margin: 0 0 30px 0;
    }

    .return-details {
      background: #fff3e0;
      padding: 25px;
      border-radius: 12px;
      margin-bottom: 30px;
      border-left: 4px solid #f57c00;
      text-align: left;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #ffe0b2;
    }

    .detail-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .detail-label {
      font-size: 14px;
      color: #666;
      font-weight: 500;
    }

    .detail-value {
      font-size: 15px;
      color: #1a1a1a;
      font-weight: 600;
    }

    .refund-info {
      background: #e8f5e9;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      border-left: 4px solid #1b944e;
    }

    .refund-label {
      font-size: 13px;
      color: #666;
      margin-bottom: 8px;
    }

    .refund-amount {
      font-size: 28px;
      font-weight: 700;
      color: #1b944e;
    }

    .action-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
    }

    .btn-primary {
      flex: 1;
      padding: 14px 24px;
      font-size: 15px;
      font-weight: 600;
      color: white;
      background: linear-gradient(135deg, #1b944e 0%, #157a3a 100%);
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(27, 148, 78, 0.3);
      text-decoration: none;
      display: inline-block;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #157a3a 0%, #0f5a2a 100%);
      box-shadow: 0 6px 20px rgba(27, 148, 78, 0.4);
      transform: translateY(-2px);
    }

    .btn-secondary {
      flex: 1;
      padding: 14px 24px;
      font-size: 15px;
      font-weight: 600;
      color: #0358b4;
      background: rgba(3, 88, 180, 0.1);
      border: 2px solid #0358b4;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .btn-secondary:hover {
      background: rgba(3, 88, 180, 0.15);
      transform: translateY(-2px);
    }

    .return-note {
      font-size: 12px;
      color: #999;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #e0e0e0;
    }
  </style>
</head>

<body>

<div class="navbar">
  <a href="my_events.php">Back to Tickets</a>
  <a href="logout.php">Logout</a>
</div>

<div class="return-container">
  <div class="return-wrapper">
    <div class="return-icon">↩️</div>
    <h1 class="return-title">Ticket Returned</h1>
    <p class="return-subtitle">Your ticket has been successfully returned</p>

    <div class="return-details">
      <div class="detail-item">
        <span class="detail-label">Event</span>
        <span class="detail-value"><?php echo $event_title; ?></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Original Price</span>
        <span class="detail-value">$<?php echo $refund_amount; ?></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Return Date</span>
        <span class="detail-value"><?php echo date('M d, Y'); ?></span>
      </div>
    </div>

    <div class="refund-info">
      <div class="refund-label">Refund Amount</div>
      <div class="refund-amount">$<?php echo $refund_amount; ?></div>
    </div>

    <div class="action-buttons">
      <a href="events.php" class="btn-primary">Browse More Events</a>
      <a href="my_events.php" class="btn-secondary">View Tickets</a>
    </div>

    <div class="return-note">
      The refund will be processed to your original payment method within 5-7 business days.
    </div>
  </div>
</div>

</body>
</html>
