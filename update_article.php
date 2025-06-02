<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'website_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the article ID is provided
if (isset($_POST['article_id'])) {
    $article_id = $_POST['article_id'];

    // Fetch the article details
    $query = "SELECT * FROM articles WHERE id = '$article_id'";
    $result = mysqli_query($conn, $query);
    $article = mysqli_fetch_assoc($result);
    
    if (!$article) {
        echo "Article not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

// Handle update form submission
if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Handle image upload if provided
    $image = $article['image']; // Keep the old image by default
    if (!empty($_FILES['image']['name'])) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    // Update the article in the database
    $update_query = "UPDATE articles SET title = '$title', content = '$content', image = '$image' WHERE id = '$article_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: view_articles.php");
        exit;
    } else {
        echo "Error updating article: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Article</title>
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

        header {
            background-color: #f4f7f6;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 40px;
            text-align: center;
        }

        header h1 {
            font-size: 32px;
            color: #D70383;
            margin-bottom: 10px;
        }

        header p {
            color: #7f8c8d;
            font-size: 18px;
        }

        form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        form label {
            font-size: 16px;
            color: #34495e;
            display: block;
            margin-bottom: 8px;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form img {
            margin-bottom: 20px;
        }

        form button {
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

        form button:hover {
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
            <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
            <a href="view_articles.php" class="nav-link">View Articles</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Update Article</h1>
            <p>Modify the details of your article below.</p>
        </header>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($article['id']); ?>">

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($article['content']); ?></textarea>

            <label for="image">Upload New Image:</label>
            <input type="file" id="image" name="image">
            <?php if (!empty($article['image'])): ?>
                <p>Current Image:</p>
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Article Image" width="100">
            <?php endif; ?>

            <button type="submit" name="update">Update Article</button>
        </form>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; 2024 Your Website. All rights reserved.
    </footer>

</body>
</html>