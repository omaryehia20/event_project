<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'organizer') {
    die("Access denied");
}

$title = $_POST['title'];
$description = $_POST['description'];
$date = $_POST['date'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO events (title, description, date, created_by)
        VALUES ('$title','$description','$date','$user_id')";

if ($conn->query($sql)) {
    echo "Event submitted for approval!";
} else {
    echo "Error: " . $conn->error;
}
?>