<?php
session_start();
include 'config.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];


$check = $conn->query("SELECT * FROM users WHERE email='$email'");
if($check->num_rows > 0){
    header("Location: register.html?error=email_exists");
    exit();
}


$sql = "INSERT INTO users (username, email, password, role)
VALUES ('$username','$email','$password','$role')";

if ($conn->query($sql) ===TRUE) {

    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['role'] = $role;

    header("Location: dashboard.php");
    exit();

} else {
    header("Location: register.html?error=register_failed");
    exit();
}
?>