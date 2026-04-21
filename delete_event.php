<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'organizer') {
    die("Access denied");
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// make sure user deletes only their own events
$conn->query("DELETE FROM events WHERE id=$id AND created_by=$user_id");

header("Location: my_events.php");
?>