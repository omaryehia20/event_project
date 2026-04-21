<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Welcome <?php echo $_SESSION['role']; ?></h2>

<?php if ($_SESSION['role'] == 'admin') { ?>
    <a href="admin.php">Admin Panel</a><br>

<?php } elseif ($_SESSION['role'] == 'organizer') { ?>
    <a href="create_event.html">Create Event</a><br>
    <a href="my_events.php">My Events</a><br>

<?php } elseif ($_SESSION['role'] == 'user') { ?>
    <a href="events.php">View Events</a><br>
<?php } ?>

<br>
<a href="logout.php">Logout</a>

</body>
</html>