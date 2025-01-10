<?php
require_once("configuration/config.php");
session_start(); // Start the session

// Redirect to login if the user isn't logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the username parameter is set
if (!isset($_GET['username'])) {
    die("No user specified.");
}

// Fetch user information based on the username
$username = trim($_GET['username']);
try {
    $query = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $query->execute(['username' => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("Error fetching user information: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($user['username']); ?>'s Profile - WeSpace</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap"
    rel="stylesheet"
  />
  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Body & Main Font */
    body {
      font-family: 'Open Sans', sans-serif;
      background: #f0f2f5;
      color: #333;
      line-height: 1.6;
    }

    /* Profile Header */
    .profile-header {
      background: #4267b2;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .profile-header h1 {
      font-size: 24px;
      margin-bottom: 5px;
    }

    .profile-header p {
      font-size: 16px;
    }

    /* Profile Icon */
    .profile-icon {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: white;
      margin: 0 auto 15px;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .profile-icon img {
      width: 80px;
      height: 80px;
    }

    /* User Information */
    .user-info {
      max-width: 600px;
      margin: 20px auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      padding: 20px;
      text-align: center;
    }

    .user-info h2 {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .user-info p {
      font-size: 16px;
      color: #555;
    }

    /* Back Button */
    .back-button {
      display: block;
      text-align: center;
      margin: 20px auto;
      text-decoration: none;
      color: white;
      background: #4267b2;
      padding: 10px 20px;
      border-radius: 25px;
      font-size: 16px;
      transition: background 0.2s ease;
    }

    .back-button:hover {
      background: #365899;
    }
  </style>
</head>
<body>
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-icon">
      <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="User Icon" />
    </div>
    <h1><?php echo htmlspecialchars($user['username']); ?></h1>
    <p>Welcome to <?php echo htmlspecialchars($user['username']); ?>'s profile!</p>
  </div>

  <!-- User Information Section -->
  <div class="user-info">
    <h2>About <?php echo htmlspecialchars($user['username']); ?></h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
  </div>

  <!-- Back Button -->
  <a href="home.php" class="back-button">← Back to Home</a>
</body>
</html>