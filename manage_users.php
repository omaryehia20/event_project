<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Verify user exists and delete
    $delete_result = $conn->query("DELETE FROM users WHERE id='$delete_id' AND role!='admin'");
    
    if ($delete_result) {
        header("Location: manage_users.php?success=1");
        exit();
    }
}

$users_result = $conn->query("SELECT * FROM users WHERE role IN ('user', 'organizer')");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Users - Evently</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="manage-users.css">
</head>

<body>

<header class="header-bar">
    <img src="images/logofinal.png" alt="Evently Logo" class="logo">
</header>

<div class="manage-container">
  <div class="manage-header">
    <h1 class="manage-title">Manage Users</h1>
    <p class="manage-subtitle">View and manage user accounts</p>
  </div>

  <?php if (isset($_GET['success'])) { ?>
    <div class="success-message">✓ User deleted successfully!</div>
  <?php } ?>

  <?php if ($users_result->num_rows > 0) { ?>
    <div class="users-table-wrapper">
      <table class="users-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $users_result->fetch_assoc()) { ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id']); ?></td>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td>
                <span class="role-badge role-<?php echo strtolower($row['role']); ?>">
                  <?php echo htmlspecialchars($row['role']); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($row['created_at'] ?? 'N/A'); ?></td>
              <td>
                <a href="manage_users.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">
                  Delete
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } else { ?>
    <div class="users-table-wrapper">
      <div class="no-users">
        <span class="no-users-icon">👥</span>
        <h3 class="no-users-title">No Users Found</h3>
        <p class="no-users-text">There are no users or organizers to manage at the moment.</p>
      </div>
    </div>
  <?php } ?>

  <div style="text-align: center;">
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
  </div>
</div>

</body>
</html>