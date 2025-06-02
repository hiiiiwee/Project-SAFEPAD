<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'website_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Check if 'id' is set in URL
$article_id = isset($_GET['id']) ? $_GET['id'] : null;
$article = null; // Store article data

if ($article_id) {
    // Fetch article only if ID is available
    $query = "SELECT * FROM articles WHERE id = '$article_id'";
    $result = mysqli_query($conn, $query);

    // Check if query was successful and contains data
    if ($result && mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);
    } else {
        echo "<p style='color:red; text-align:center;'>Error: Article not found.</p>";
    }
} else {
    echo "<p style='color:red; text-align:center;'>Error: Article ID is missing.</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Article</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and Layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Section */
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

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .article-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        .article-container h2 {
            font-size: 28px;
            color: #D70383;
            margin-bottom: 20px;
        }

        .article-container img {
            max-width: 100%;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .article-container p {
            font-size: 18px;
            color: #34495e;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .feedback-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .feedback-form button {
            background-color: #D70383;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .feedback-form button:hover {
            background-color: #d537f5;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #f4f7f6;
            color: #7f8c8d;
            font-size: 14px;
            margin-top: auto;
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
            <a href="user_dashboard.php" class="nav-link">Home</a>
            <a href="quiz.php" class="nav-link">Quiz</a>
        <a href="logout.php" class="nav-link">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="article-container">
            <?php if ($article): ?>
                <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                <?php if (!empty($article['image'])): ?>
                    <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Article Image">
                <?php endif; ?>
                <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>

                <!-- Feedback Form -->
                <form action="submit_feedback.php" method="post" class="feedback-form">
                    <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                    <textarea name="feedback" placeholder="Enter your feedback"></textarea>
                    <button type="submit">Submit Feedback</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; 2024 Your Website. All rights reserved.
    </footer>

</body>
</html>
