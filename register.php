<?php
session_start();
include 'config.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$sql = "INSERT INTO users (username, email, password, role)
VALUES ('$username','$email','$password','$role')";

if ($conn->query($sql) ===TRUE) {
    // echo "Registered successfully!";
    $_SESSION['role'] = $role;
    $_SESSION['username'] = $username;

    header("Location: dashboard.php");
    exit();

} else {
    echo "Error: " . $conn->error;
}
?>