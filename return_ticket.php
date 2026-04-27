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

// Check if ticket_id is provided
if (!isset($_GET['ticket_id'])) {
    header("Location: my_events.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$ticket_id = $_GET['ticket_id'];

// Verify ticket belongs to this user
$verify = $conn->query("SELECT t.id, e.title, e.price FROM tickets t 
                       INNER JOIN events e ON t.event_id = e.id 
                       WHERE t.id = '$ticket_id' AND t.user_id = '$user_id'");

$ticket = $verify->fetch_assoc();

if (!$ticket) {
    header("Location: my_events.php?error=invalid");
    exit();
}

// Delete the ticket
$delete_sql = "DELETE FROM tickets WHERE id = '$ticket_id'";

if ($conn->query($delete_sql) === TRUE) {
    $_SESSION['return_event_title'] = $ticket['title'];
    $_SESSION['return_amount'] = $ticket['price'];
    header("Location: return_success.php");
    exit();
} else {
    header("Location: my_events.php?error=failed");
    exit();
}
?>
