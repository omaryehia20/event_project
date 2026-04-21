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
  <title>Admin Panel</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="navbar">
  <a href="dashboard.php">Home</a>
  <a href="logout.php">Logout</a>
</div>

<h2>Pending Events</h2>

<?php while ($row = $result->fetch_assoc()) { ?>

  <div class="card">
    <h3><?php echo $row['title']; ?></h3>
    <p><?php echo $row['description']; ?></p>
    <p><b>Date:</b> <?php echo $row['date']; ?></p>

    <a class="approve-btn" href="approve.php?id=<?php echo $row['id']; ?>">
      Approve
    </a>
  </div>

<?php } ?>

<a href="dashboard.php">Back</a>

</body>
</html>