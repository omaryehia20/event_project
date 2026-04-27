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
  <style>
    .manage-container {
      max-width: 1200px;
      margin: 60px auto;
      padding: 0 20px;
    }

    .manage-header {
      text-align: center;
      margin-bottom: 50px;
    }

    .manage-title {
      font-size: 36px;
      font-weight: 700;
      color: white;
      margin: 0 0 10px 0;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
      letter-spacing: -0.5px;
    }

    .manage-subtitle {
      font-size: 18px;
      color: rgba(255, 255, 255, 0.8);
      margin: 0;
      font-weight: 300;
    }

    .success-message {
      background: rgba(27, 148, 78, 0.2);
      border: 1px solid #1b944e;
      color: #1b944e;
      padding: 15px 20px;
      border-radius: 8px;
      margin-bottom: 30px;
      text-align: center;
      font-weight: 600;
    }

    .users-table-wrapper {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border-radius: 20px;
      overflow: hidden;
      margin-bottom: 30px;
    }

    .users-table {
      width: 100%;
      border-collapse: collapse;
    }

    .users-table thead {
      background: linear-gradient(135deg, #0358b4 0%, #025fa3 100%);
    }

    .users-table th {
      color: white;
      padding: 20px;
      text-align: left;
      font-weight: 600;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .users-table td {
      padding: 18px 20px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
      color: #333;
      font-size: 14px;
    }

    .users-table tbody tr:hover {
      background: rgba(3, 88, 180, 0.05);
    }

    .users-table tbody tr:last-child td {
      border-bottom: none;
    }

    .role-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .role-user {
      background: rgba(3, 88, 180, 0.2);
      color: #0358b4;
    }

    .role-organizer {
      background: rgba(155, 89, 182, 0.2);
      color: #9b59b6;
    }

    .delete-btn {
      display: inline-block;
      padding: 8px 16px;
      font-size: 12px;
      font-weight: 600;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
    }

    .delete-btn:hover {
      background: linear-gradient(135deg, #b71c1c 0%, #ad1457 100%);
      transform: scale(1.05);
    }

    .no-users {
      text-align: center;
      padding: 60px 30px;
    }

    .no-users-icon {
      font-size: 64px;
      margin-bottom: 20px;
      display: block;
    }

    .no-users-title {
      font-size: 24px;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0 0 15px 0;
    }

    .no-users-text {
      font-size: 16px;
      color: #666;
      margin: 0;
      line-height: 1.5;
    }

    .back-btn {
      display: inline-block;
      padding: 12px 24px;
      font-size: 14px;
      font-weight: 600;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      background: linear-gradient(135deg, #666 0%, #555 100%);
    }

    .back-btn:hover {
      background: linear-gradient(135deg, #555 0%, #444 100%);
      transform: translateY(-2px);
    }

    @media (max-width: 768px) {
      .users-table {
        font-size: 12px;
      }

      .users-table th, .users-table td {
        padding: 12px 10px;
      }

      .delete-btn {
        padding: 6px 12px;
        font-size: 10px;
      }
    }
  </style>
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