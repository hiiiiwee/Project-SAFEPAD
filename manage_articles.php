<?php 
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'website_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image = $_FILES['image']['name'];

    // Set the upload directory on the server
    $uploadDir = "uploads/articles/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Ensure the directory exists
    }

    $imagePath = $uploadDir . basename($image);

    // Move the uploaded image to the server
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $userId = 1; // Replace with dynamic user ID, e.g., from $_SESSION['user_id']
            $sql = "INSERT INTO `articles` (`title`, `content`, `image`, `user_id`) 
                    VALUES ('$title', '$content', '$imagePath', '$userId')";
            if (mysqli_query($conn, $sql)) {
                header("location:view_articles.php");
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "No image uploaded or there was an error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
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

        form input, form textarea, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
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
            <a href="view_feedback.php" class="nav-link">Feedback</a>
            <a href="manage_quizzes.php" class="nav-link">Manage Quizzes</a>
            <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
            <a href="view_articles.php" class="nav-link">View Articles</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Add New Article</h1>
            <p>Fill out the form below to create a new article.</p>
        </header>

        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" required></textarea>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image">

            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; 2024 Your Website. All rights reserved.
    </footer>

</body>
</html>
