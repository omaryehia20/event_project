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

// Check if success data exists
if (!isset($_SESSION['success_event_id'])) {
    header("Location: events.php");
    exit();
}

$event_id = $_SESSION['success_event_id'];
$payment_method = $_SESSION['success_payment_method'];

// Get event details
$result = $conn->query("SELECT * FROM events WHERE id='$event_id'");
$event = $result->fetch_assoc();

// Clear session variables
unset($_SESSION['success_event_id']);
unset($_SESSION['success_payment_method']);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Purchase Successful - Evently</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .success-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: calc(100vh - 150px);
      padding: 20px;
    }

    .success-wrapper {
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

    .success-icon {
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

    .success-title {
      font-size: 32px;
      font-weight: 700;
      color: #1b944e;
      margin: 0 0 10px 0;
      letter-spacing: -0.5px;
    }

    .success-subtitle {
      font-size: 16px;
      color: #666;
      margin: 0 0 30px 0;
    }

    .ticket-details {
      background: #f8f9fa;
      padding: 25px;
      border-radius: 12px;
      margin-bottom: 30px;
      border-left: 4px solid #1b944e;
      text-align: left;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #e0e0e0;
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

    .payment-badge {
      display: inline-block;
      padding: 8px 16px;
      background: linear-gradient(135deg, #0358b4 0%, #025fa3 100%);
      color: white;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      margin-top: 15px;
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

    .confirmation-number {
      background: #e8f5e9;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-family: 'Courier New', monospace;
      font-size: 12px;
      color: #1b944e;
      font-weight: 600;
      word-break: break-all;
    }
  </style>
</head>

<body>

<div class="navbar">
  <a href="events.php">Back to Events</a>
  <a href="logout.php">Logout</a>
</div>

<div class="success-container">
  <div class="success-wrapper">
    <div class="success-icon">✅</div>
    <h1 class="success-title">Purchase Successful!</h1>
    <p class="success-subtitle">Your ticket has been confirmed</p>

    <div class="ticket-details">
      <div class="detail-item">
        <span class="detail-label">Event</span>
        <span class="detail-value"><?php echo $event['title']; ?></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Date</span>
        <span class="detail-value"><?php echo $event['date']; ?></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Location</span>
        <span class="detail-value"><?php echo $event['location']; ?></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Amount Paid</span>
        <span class="detail-value">$<?php echo $event['price']; ?></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Payment Method</span>
        <span class="detail-value"><?php echo ucfirst($payment_method); ?></span>
      </div>
    </div>

    <div class="confirmation-number">
      Confirmation #<?php echo date('YmdHis') . mt_rand(1000, 9999); ?>
    </div>

    <div class="action-buttons">
      <a href="events.php" class="btn-primary">Browse More Events</a>
      <a href="my_events.php" class="btn-secondary">View My Tickets</a>
    </div>
  </div>
</div>

</body>
</html>
