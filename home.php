<?php
require_once("configuration/config.php");
session_start(); // Start the session

// Redirect to login if the user isn't logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect the user to the login page
    exit();
}

// Handle search functionality
$searchResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_user'])) {
    $searchUsername = trim($_POST['search_user']);
    try {
        $searchQuery = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $searchQuery->execute(['username' => $searchUsername]);
        $searchResult = $searchQuery->fetch(PDO::FETCH_ASSOC);
        if ($searchResult) {
            // Redirect to user profile page with user data
            header("Location: user-profile.php?username=" . urlencode($searchUsername));
            exit();
        } else {
            $searchError = "User not found.";
        }
    } catch (PDOException $e) {
        die("Error searching for user: " . $e->getMessage());
    }
}

// Fetch posts along with the username from the database
try {
    $query = $pdo->prepare(
        "SELECT user_posts.*, users.username 
         FROM user_posts 
         JOIN users ON user_posts.user_id = users.id 
         ORDER BY user_posts.created_at DESC"
    );
    $query->execute();
    $posts = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching posts: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <!-- Open Sans Font -->
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap"
    rel="stylesheet"
  />
  <!-- Poppins Font -->
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
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

    /* Header Section */
    header {
      background: #4267b2;
      color: white;
      padding: 10px 20px;
      text-align: left;
      font-size: 20px;
      font-weight: 600;
    }

    .logo {
      font-weight: 600;
      display: inline-block;
      font-family: 'Poppins', sans-serif;
    }

    /* Search Box */
    .search-box {
      margin: 10px auto;
      max-width: 500px;
      text-align: center;
      display: flex;
      align-items: center;
      gap: 10px; /* Add spacing between the elements */
    }

    .search-box form {
      display: flex;
      flex-grow: 1;
    }

    .search-box input {
      width: calc(100% - 110px); /* Adjusted width for the post button */
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 25px;
      outline: none;
      font-size: 14px;
    }

    .search-box input::placeholder {
      font-family: 'Open Sans', sans-serif;
    }

    .post-btn {
      padding: 10px 20px;
      background-color: #4267b2;
      color: white;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-family: 'Open Sans', sans-serif;
      font-size: 14px;
      font-weight: 600;
      text-align: center;
      text-decoration: none;
    }

    .post-btn:hover {
      background-color: #365899;
    }

    .search-error {
      color: red;
      font-size: 14px;
      margin-top: 5px;
    }

    /* Main Section */
    main {
      padding: 10px 20px;
    }

    /* Post Section */
    .post-container {
      max-width: 600px;
      margin: 0 auto;
    }

    .post {
      background: white;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      padding: 15px;
      margin: 10px 0;
      transition: box-shadow 0.2s ease;
    }

    .post:hover {
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    /* Post Header */
    .post-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
      font-size: 14px;
      color: #555;
    }

    .user-profile {
      font-weight: 600;
    }

    .post-time {
      color: #777;
      font-size: 12px;
    }

    /* Post Content */
    .post-content {
      font-size: 15px;
      margin-bottom: 10px;
      color: #555;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .search-box {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
      }

      .search-box input {
        width: 100%;
        margin-bottom: 10px;
      }

      .post-btn {
        width: auto;
        margin: 0 auto;
      }

      main {
        padding: 5px 10px;
      }

      .post {
        padding: 10px;
      }

      .post-content {
        font-size: 14px;
      }

      header {
        font-size: 18px;
      }
    }
</style>
</head>
<body>
  <!-- Header with user session greeting -->
  <header>
    <div class="logo">Kleek</div>
    <div class="user-greeting">👤 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
  </header>

  <!-- Search Box -->
  <div class="search-box">
    <form method="POST" action="">
      <input type="text" name="search_user" placeholder="Search for a user..." required />
    </form>
    <a href="create-post.php" class="post-btn">Post</a>
    <?php if (isset($searchError)): ?>
      <div class="search-error"><?php echo htmlspecialchars($searchError); ?></div>
    <?php endif; ?>
  </div>

  <!-- Main Section with Posts -->
  <main>
    <div class="post-container">
      <?php if (empty($posts)): ?>
        <div class="post">
          <div class="post-content" style="text-align: center; font-weight: 600; color: #555;">
            No posts available. Be the first to create one!
          </div>
        </div>
      <?php else: ?>
        <?php foreach ($posts as $post): ?>
          <div class="post">
            <div class="post-header">
              <div class="user-profile">👤 <?php echo htmlspecialchars($post['username']); ?></div>
              <div class="post-time">
                <?php
                $createdAt = new DateTime($post['created_at']);
                echo $createdAt->format('F j, Y, g:i a');
                ?>
              </div>
            </div>
            <div class="post-content">
              <strong><?php echo htmlspecialchars($post['title']); ?></strong><br />
              <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>