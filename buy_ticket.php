<?php
session_start();
include 'config.php';

// check if user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'visa';

// Validate Visa payment if selected
if ($payment_method === 'visa') {
    $card_holder = isset($_POST['card_holder']) ? trim($_POST['card_holder']) : '';
    $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : '';
    $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '';
    $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';
    
    // Basic validation
    if (empty($card_holder) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        header("Location: checkout.php?event_id=$event_id&error=missing_fields");
        exit();
    }
    
    // Validate card number format (last 4 digits stored)
    if (!preg_match('/^\d{4}$/', $card_number)) {
        header("Location: checkout.php?event_id=$event_id&error=invalid_card");
        exit();
    }
    
    // Validate expiry format
    if (!preg_match('/^\d{2}\/\d{2}$/', $expiry_date)) {
        header("Location: checkout.php?event_id=$event_id&error=invalid_expiry");
        exit();
    }
    
    // Validate CVV
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        header("Location: checkout.php?event_id=$event_id&error=invalid_cvv");
        exit();
    }
}

// insert ticket
$sql = "INSERT INTO tickets (user_id, event_id)
        VALUES ('$user_id', '$event_id')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success_event_id'] = $event_id;
    $_SESSION['success_payment_method'] = $payment_method;
    header("Location: success.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>