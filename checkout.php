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

$event_id = $_GET['event_id'];

// Get event details
$result = $conn->query("SELECT * FROM events WHERE id='$event_id' AND status='approved'");
$event = $result->fetch_assoc();

if (!$event) {
    header("Location: events.php");
    exit();
}

// Get user details
$user_result = $conn->query("SELECT * FROM users WHERE id='" . $_SESSION['user_id'] . "'");
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - Evently</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="checkout.css">
</head>

<body>

<div class="navbar">
  <a href="events.php">Back to Events</a>
  <a href="logout.php">Logout</a>
</div>

<div class="checkout-container">
  <div class="checkout-wrapper">
    <h1 class="checkout-title">Order Summary</h1>

    <img src="images/<?php echo $event['image']; ?>" alt="<?php echo $event['title']; ?>" class="event-img-checkout">

    <div class="order-summary">
      <div class="order-item">
        <span class="order-label">Event Title</span>
        <span class="order-value"><?php echo $event['title']; ?></span>
      </div>
      <div class="order-item">
        <span class="order-label">Date</span>
        <span class="order-value"><?php echo $event['date']; ?></span>
      </div>
      <div class="order-item">
        <span class="order-label">Location</span>
        <span class="order-value"><?php echo $event['location']; ?></span>
      </div>
      <div class="order-item">
        <span class="order-label">Attendee</span>
        <span class="order-value"><?php echo $user['username']; ?></span>
      </div>
      <div class="order-item">
        <span class="order-label">Email</span>
        <span class="order-value"><?php echo $user['email']; ?></span>
      </div>
    </div>

    <div class="total-section">
      <div class="total-row">
        <span>Total Amount</span>
        <span class="total-price">$<?php echo $event['price']; ?></span>
      </div>
    </div>

    <div class="payment-section">
      <label class="payment-label">Payment Method</label>
      <div class="payment-options">
        <div class="payment-option">
          <input type="radio" id="visa" name="payment_method" value="visa" required checked>
          <label for="visa" class="payment-option-label">Credit Card</label>
        </div>
        <div class="payment-option">
          <input type="radio" id="cash" name="payment_method" value="cash" required>
          <label for="cash" class="payment-option-label">Cash</label>
        </div>
      </div>
    </div>

    <div id="card-form" class="card-form visible">
      <h3 style="margin-top: 0; color: #1a1a1a; font-size: 16px; margin-bottom: 20px;">Card Details</h3>
      
      <div class="form-group">
        <label class="form-label">Cardholder Name</label>
        <input type="text" id="card_holder" name="card_holder" class="form-input" placeholder="omar yehia" maxlength="50">
        <div class="error-message" id="card_holder_error"></div>
      </div>

      <div class="form-group">
        <label class="form-label">Card Number</label>
        <input type="text" id="card_number" name="card_number" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19">
        <div class="error-message" id="card_number_error"></div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Expiry Date (MM/YY)</label>
          <input type="text" id="expiry_date" name="expiry_date" class="form-input" placeholder="12/25" maxlength="5">
          <div class="error-message" id="expiry_date_error"></div>
        </div>
        <div class="form-group">
          <label class="form-label">CVV</label>
          <input type="text" id="cvv" name="cvv" class="form-input" placeholder="123" maxlength="4">
          <div class="error-message" id="cvv_error"></div>
        </div>
      </div>
    </div>

    <form action="buy_ticket.php" method="POST" style="margin-bottom: 0;" id="checkout-form">
      <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
      <input type="hidden" name="payment_method" id="payment_method_hidden" value="visa">
      <input type="hidden" name="card_holder" id="card_holder_hidden" value="">
      <input type="hidden" name="card_number" id="card_number_hidden" value="">
      <input type="hidden" name="expiry_date" id="expiry_date_hidden" value="">
      <input type="hidden" name="cvv" id="cvv_hidden" value="">
      <div class="button-group">
        <a href="events.php" class="back-btn">Cancel</a>
        <button type="submit" class="checkout-btn">Complete Purchase</button>
      </div>
    </form>
  </div>
</div>

<script>
  const visaRadio = document.getElementById('visa');
  const cashRadio = document.getElementById('cash');
  const cardForm = document.getElementById('card-form');
  const cardInputs = {
    card_holder: document.getElementById('card_holder'),
    card_number: document.getElementById('card_number'),
    expiry_date: document.getElementById('expiry_date'),
    cvv: document.getElementById('cvv')
  };
  
  const checkoutForm = document.getElementById('checkout-form');

  // Toggle card form visibility
  function toggleCardForm() {
    if (visaRadio.checked) {
      cardForm.classList.remove('hidden');
      document.getElementById('payment_method_hidden').value = 'visa';
    } else {
      cardForm.classList.add('hidden');
      document.getElementById('payment_method_hidden').value = 'cash';
      clearCardErrors();
    }
  }

  visaRadio.addEventListener('change', toggleCardForm);
  cashRadio.addEventListener('change', toggleCardForm);
  toggleCardForm();

  // Card holder name validation
  cardInputs.card_holder.addEventListener('blur', function() {
    const value = this.value.trim();
    const errorEl = document.getElementById('card_holder_error');
    
    if (value === '') {
      this.classList.add('error');
      errorEl.textContent = 'Cardholder name is required';
      errorEl.classList.add('show');
    } else if (value.length < 3) {
      this.classList.add('error');
      errorEl.textContent = 'Name must be at least 3 characters';
      errorEl.classList.add('show');
    } else if (!/^[a-zA-Z\s]+$/.test(value)) {
      this.classList.add('error');
      errorEl.textContent = 'Name can only contain letters and spaces';
      errorEl.classList.add('show');
    } else {
      this.classList.remove('error');
      errorEl.classList.remove('show');
    }
  });

  // Card number validation and formatting
  cardInputs.card_number.addEventListener('input', function() {
    let value = this.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    this.value = formattedValue;
  });

  cardInputs.card_number.addEventListener('blur', function() {
    const value = this.value.replace(/\s/g, '');
    const errorEl = document.getElementById('card_number_error');
    
    if (value === '') {
      this.classList.add('error');
      errorEl.textContent = 'Card number is required';
      errorEl.classList.add('show');
    } else if (!/^\d{13,19}$/.test(value)) {
      this.classList.add('error');
      errorEl.textContent = 'Card number must be 13-19 digits';
      errorEl.classList.add('show');
    } else if (!luhnCheck(value)) {
      this.classList.add('error');
      errorEl.textContent = 'Invalid card number';
      errorEl.classList.add('show');
    } else {
      this.classList.remove('error');
      errorEl.classList.remove('show');
    }
  });

  // Expiry date validation and formatting
  cardInputs.expiry_date.addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length >= 2) {
      value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    this.value = value;
  });

  cardInputs.expiry_date.addEventListener('blur', function() {
    const value = this.value;
    const errorEl = document.getElementById('expiry_date_error');
    
    if (value === '') {
      this.classList.add('error');
      errorEl.textContent = 'Expiry date is required';
      errorEl.classList.add('show');
    } else if (!/^\d{2}\/\d{2}$/.test(value)) {
      this.classList.add('error');
      errorEl.textContent = 'Format: MM/YY';
      errorEl.classList.add('show');
    } else {
      const [month, year] = value.split('/');
      const monthNum = parseInt(month);
      if (monthNum < 1 || monthNum > 12) {
        this.classList.add('error');
        errorEl.textContent = 'Invalid month (01-12)';
        errorEl.classList.add('show');
      } else {
        const currentYear = new Date().getFullYear() % 100;
        const currentMonth = new Date().getMonth() + 1;
        const expYear = parseInt(year);
        
        if (expYear < currentYear || (expYear === currentYear && monthNum < currentMonth)) {
          this.classList.add('error');
          errorEl.textContent = 'Card has expired';
          errorEl.classList.add('show');
        } else {
          this.classList.remove('error');
          errorEl.classList.remove('show');
        }
      }
    }
  });

  // CVV validation
  cardInputs.cvv.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').substring(0, 4);
  });

  cardInputs.cvv.addEventListener('blur', function() {
    const value = this.value;
    const errorEl = document.getElementById('cvv_error');
    
    if (value === '') {
      this.classList.add('error');
      errorEl.textContent = 'CVV is required';
      errorEl.classList.add('show');
    } else if (!/^\d{3,4}$/.test(value)) {
      this.classList.add('error');
      errorEl.textContent = 'CVV must be 3-4 digits';
      errorEl.classList.add('show');
    } else {
      this.classList.remove('error');
      errorEl.classList.remove('show');
    }
  });

  // Luhn algorithm for card validation
  function luhnCheck(num) {
    let sum = 0;
    let isEven = false;
    for (let i = num.length - 1; i >= 0; i--) {
      let digit = parseInt(num[i], 10);
      if (isEven) {
        digit *= 2;
        if (digit > 9) {
          digit -= 9;
        }
      }
      sum += digit;
      isEven = !isEven;
    }
    return sum % 10 === 0;
  }

  // Form validation on submit
  checkoutForm.addEventListener('submit', function(e) {
    const paymentMethod = document.getElementById('payment_method_hidden').value;
    
    if (paymentMethod === 'visa') {
      e.preventDefault();
      
      let isValid = true;

      // Validate cardholder name
      const cardHolderValue = cardInputs.card_holder.value.trim();
      if (!cardHolderValue || cardHolderValue.length < 3 || !/^[a-zA-Z\s]+$/.test(cardHolderValue)) {
        cardInputs.card_holder.classList.add('error');
        document.getElementById('card_holder_error').textContent = cardHolderValue === '' ? 'Cardholder name is required' : 'Invalid cardholder name';
        document.getElementById('card_holder_error').classList.add('show');
        isValid = false;
      }

      // Validate card number
      const cardNumberValue = cardInputs.card_number.value.replace(/\s/g, '');
      if (!cardNumberValue || !/^\d{13,19}$/.test(cardNumberValue) || !luhnCheck(cardNumberValue)) {
        cardInputs.card_number.classList.add('error');
        document.getElementById('card_number_error').textContent = 'Invalid card number';
        document.getElementById('card_number_error').classList.add('show');
        isValid = false;
      }

      // Validate expiry date
      const expiryValue = cardInputs.expiry_date.value;
      if (!expiryValue || !/^\d{2}\/\d{2}$/.test(expiryValue)) {
        cardInputs.expiry_date.classList.add('error');
        document.getElementById('expiry_date_error').textContent = 'Invalid expiry date';
        document.getElementById('expiry_date_error').classList.add('show');
        isValid = false;
      }

      // Validate CVV
      const cvvValue = cardInputs.cvv.value;
      if (!cvvValue || !/^\d{3,4}$/.test(cvvValue)) {
        cardInputs.cvv.classList.add('error');
        document.getElementById('cvv_error').textContent = 'Invalid CVV';
        document.getElementById('cvv_error').classList.add('show');
        isValid = false;
      }

      if (isValid) {
        // Store card details in hidden fields
        document.getElementById('card_holder_hidden').value = cardHolderValue;
        document.getElementById('card_number_hidden').value = cardNumberValue.slice(-4); // Only store last 4 digits
        document.getElementById('expiry_date_hidden').value = expiryValue;
        document.getElementById('cvv_hidden').value = cvvValue;
        
        checkoutForm.submit();
      }
    }
  });

  function clearCardErrors() {
    Object.keys(cardInputs).forEach(key => {
      cardInputs[key].classList.remove('error');
      document.getElementById(key + '_error').classList.remove('show');
    });
  }
</script>
