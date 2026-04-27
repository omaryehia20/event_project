<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'organizer') {
    die("Access denied");
}

$title = $_POST['title'];
$description = $_POST['description'];
$date = $_POST['date'];
$location = $_POST['location'];
$price = $_POST['price'];
$user_id = $_SESSION['user_id'];

$image_name = $_FILES['image']['name'];
$image_tmp = $_FILES['image']['tmp_name'];

move_uploaded_file($image_tmp, "images/" . $image_name);

$sql = "INSERT INTO events (title, description, date, location, price, image, created_by)
        VALUES ('$title','$description','$date', '$location', '$price', '$image_name', '$user_id')";

if ($conn->query($sql) === TRUE) {
     header("Location: dashboard.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>