<?php 
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'website_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch articles
$query = "SELECT * FROM articles ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $article_id = $_POST['article_id'];

    // Prepare statements
    $delete_feedback_stmt = $conn->prepare("DELETE FROM feedback WHERE article_id = ?");
    $delete_article_stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");

    // Bind parameters
    $delete_feedback_stmt->bind_param("i", $article_id);
    $delete_article_stmt->bind_param("i", $article_id);

    // Execute feedback deletion
    $delete_feedback_stmt->execute();

    // Execute article deletion
    if ($delete_article_stmt->execute()) {
        header("Location: view_articles.php");
        exit;
    } else {
        echo "Error deleting article: " . $conn->error;
    }

    // Close statements
    $delete_feedback_stmt->close();
    $delete_article_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Articles</title>
   <link rel="stylesheet" href="styles.css">
   <style>
       /* General Reset */
       * {
           margin: 0;
           padding: 0;
           box-sizing: border-box;
       }

       body {
           font-family: Arial, sans-serif;
           background-color: #f4f7f6;
           display: flex;
           flex-direction: column;
           min-height: 100vh;
       }

       .header {
           display: flex;
           justify-content: space-between;
           align-items: center;
           padding: 20px 40px;
           background-color: #fff;
           color: rgb(14, 13, 13);
           border-bottom: 3.5px solid #D70383;
       }

       .header .logo img {
           height: 60px;
       }

       .header .nav {
           display: flex;
           gap: 25px;
       }

       .header .nav-link {
           color: black;
           text-decoration: none;
           font-size: 18px;
           padding: 10px 16px;
           border-radius: 4px;
           transition: background-color 0.3s ease;
       }

       .header .nav-link:hover {
           background-color: #D70383;
           color: white;
       }

       .articles-list {
           display: grid;
           grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
           gap: 20px;
           padding: 40px 60px;
       }

       .article {
           border: 1px solid #ccc;
           padding: 20px;
           border-radius: 5px;
           background-color: #fff;
       }

       .article h2 {
           font-size: 20px;
           color: #D70383;
       }

       .article img {
           max-width: 100%;
           border-radius: 5px;
           margin: 10px 0;
       }

       .article button {
           margin-top: 10px;
           background-color: #D70383;
           color: white;
           border: none;
           padding: 10px;
           cursor: pointer;
           border-radius: 4px;
           transition: background-color 0.3s ease;
       }

       .article button:hover {
           background-color: #d537f5;
       }

       footer {
           text-align: center;
           padding: 20px;
           background-color: #f4f7f6;
           color: #7f8c8d;
           font-size: 14px;
           margin-top: auto;
       }
       .articleimg{
        height: 200px;
        width: 250px;
        margin:10px 0;
       }
       .articleimg img{
        height:100%;
        width: 100%;
        object-fit: cover;
        object-position: center;
       }
   </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <div class="logo">
            <img src="./Image/Logo.png" alt="The SafePad Logo">
        </div>
        <div class="nav">
            <a href="view_feedback.php" class="nav-link">Feedback</a>
            <a href="manage_quizzes.php" class="nav-link">Manage Quizzes</a>
            <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
            <a href="manage_articles.php" class="nav-link">Add Article</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1 style="text-align: center; margin: 20px 0;">Articles</h1>
        <div class="articles-list">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='article'>";
                echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
                if (!empty($row['image'])) {
                    echo "<div class = 'articleimg'><img src='" . htmlspecialchars($row['image']) . "' alt='Article Image'></div>";
                }
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                echo "<p><em>Published on: " . htmlspecialchars($row['created_at']) . "</em></p>";
                
                // Update form
                echo "<form method='POST' action='update_article.php'>";
                echo "<input type='hidden' name='article_id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<button type='submit' name='edit'>Edit</button>";
                echo "</form>";

                // Delete form
                echo "<form method='POST' action='' onsubmit='return confirm(\"Are you sure you want to delete this article?\");'>";
                echo "<input type='hidden' name='article_id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<button type='submit' name='delete'>Delete</button>";
                echo "</form>";

                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; 2024 Your Website. All rights reserved.
    </footer>
</body>
</html>
