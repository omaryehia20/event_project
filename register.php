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
    // $_SESSION['role'] = $role;
    // $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['role'] = $role;

    header("Location: dashboard.php");
    exit();

} else {
    echo "Error: " . $conn->error;
}
?>