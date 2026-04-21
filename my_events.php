<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'organizer') {
    die("Access denied");
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM events WHERE created_by = $user_id");
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Events</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="navbar">
  <a href="dashboard.php">Home</a>
  <a href="logout.php">Logout</a>
</div>

<h2>My Events</h2>

<?php while ($row = $result->fetch_assoc()) { ?>

  <div class="card">
    <h3><?php echo $row['title']; ?></h3>
    <p><?php echo $row['description']; ?></p>
    <p><b>Date:</b> <?php echo $row['date']; ?></p>
    <p><b>Status:</b> <?php echo $row['status']; ?></p>

    <a class="delete-btn" 
   href="delete_event.php?id=<?php echo $row['id']; ?>" 
   onclick="return confirm('Are you sure?')">
   Delete
   </a>
  </div>

<?php } ?>

<a href="dashboard.php">Back</a>

</body>
</html>