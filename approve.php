<?php
include 'config.php';

$id = $_GET['id'];

$conn->query("UPDATE events SET status='approved' WHERE id=$id");

header("Location: admin.php");
?>