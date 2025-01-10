<?php
// Ensure the session is started to identify the logged-in user
session_start();

// Include the database connection file
require_once 'configuration/config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user input from the form
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session

    // Check for empty fields
    if (!empty($title) && !empty($content) && !empty($user_id)) {
        // Insert the post into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO user_posts (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$user_id, $title, $content]);

            // Redirect to a success page or back to the main page
            header("Location: home.php");
            exit();
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Show an error if fields are empty
        echo "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create a Post - WeSpace</title>
  <!-- Import Open Sans from Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
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
      font-family: 'Open Sans', sans-serif;
    }

    .logo {
      font-weight: 600;
      display: inline-block;
      font-family: 'Open Sans', sans-serif;
    }

    /* Main Section */
    main {
      padding: 20px 10px;
      max-width: 800px;
      margin: 10px auto;
      font-family: 'Open Sans', sans-serif;
    }

    /* Form Section */
    .form-container {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      margin: 20px 0;
      max-width: 700px;
      font-family: 'Open Sans', sans-serif;
    }

    .form-container h2 {
      font-size: 18px;
      color: #555;
      margin-bottom: 10px;
      font-family: 'Open Sans', sans-serif;
    }

    /* Form Styling */
    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin: 10px 0 5px;
      font-size: 14px;
      color: #555;
      font-family: 'Open Sans', sans-serif;
    }

    input[type="text"],
    textarea {
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-size: 14px;
      outline: none;
      transition: border-color 0.2s ease;
      font-family: 'Open Sans', sans-serif;
    }

    input[type="text"]:focus,
    textarea:focus {
      border-color: #4267b2;
    }

    button[type="submit"] {
      background: #4267b2;
      color: white;
      border: none;
      border-radius: 30px;
      padding: 10px 15px;
      cursor: pointer;
      font-size: 15px;
      transition: background-color 0.2s ease;
      font-family: 'Open Sans', sans-serif;
    }

    button[type="submit"]:hover {
      background-color: #365899;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      main {
        padding: 10px;
      }

      .form-container {
        padding: 15px;
      }

      input[type="text"],
      textarea {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>
  <!-- Header Section -->
  <header>
    <div class="logo">WeSpace</div>
  </header>

  <!-- Main Section -->
  <main>
    <div class="form-container">
      <h2>Create a New Post</h2>
      <form action="" method="POST">
        <!-- Title Field -->
        <label for="title">Post Title</label>
        <input type="text" id="title" name="title" required />

        <!-- Content Field -->
        <label for="content">Post Content</label>
        <textarea
          id="content"
          name="content"
          rows="5"
          required
          placeholder="Write something here..."
        ></textarea>

        <!-- Submit Button -->
        <button type="submit">Post</button>
      </form>
    </div>
  </main>
</body>
</html>