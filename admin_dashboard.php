<?php  
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'admin') {
    header('Location:../login.html'); // Redirect to login if unauthorized
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'website_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add/Edit/Delete operations for articles
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = "uploads/";

    if (isset($_POST['add'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $author = $_SESSION['uname'];
        $imagePath = '';

        if (!empty($_FILES['image']['name'])) {
            $imageName = basename($_FILES['image']['name']);
            $targetFilePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = $targetFilePath;
            }
        }

        $stmt = $conn->prepare("INSERT INTO articles (title, content, image, author) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $content, $imagePath, $author);
        $stmt->execute();
        $stmt->close();
    }

    elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $imagePath = $_POST['existing_image'];

        if (!empty($_FILES['image']['name'])) {
            $imageName = basename($_FILES['image']['name']);
            $targetFilePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = $targetFilePath;
            }
        }

        $stmt = $conn->prepare("UPDATE articles SET title = ?, content = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $imagePath, $id);
        $stmt->execute();
        $stmt->close();
    }

    elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $result = $conn->query("SELECT image FROM articles WHERE id = '$id'");
        $article = $result->fetch_assoc();
        if (!empty($article['image']) && file_exists($article['image'])) {
            unlink($article['image']);
        }

        $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all articles
$articles_result = $conn->query("SELECT * FROM articles ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The SafePad</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <!-- Header Section -->
    <header id="navi" class="header">
        <div class="logo">
            <img src="./Image/Logo.png" alt="The SafePad Logo">
        </div>
        <nav class="nav">
            <a href="view_feedback.php" class="nav-link">Feedback</a>
            <a href="view_articles.php" class="nav-link">Articles</a>
            <a href="manage_quizzes.php" class="nav-link">Quiz</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Welcome, Admin!</h1>
            <p>Manage articles, quizzes, and more to support menstrual hygiene advocacy.</p>
        </header>

        <!-- Articles Management -->
        <div class="cards">
            <div class="card">
                <h3>Manage Articles</h3>
                <p>Add, edit, or delete articles for The SafePad platform.</p>
                <button onclick="location.href='manage_articles.php'">Go to Articles</button>
            </div>

            <div class="card">
                <h3>Manage Quizzes</h3>
                <p>Edit quizzes to help users learn about menstrual hygiene.</p>
                <button onclick="location.href='manage_quizzes.php'">Go to Quizzes</button>
            </div>
            <div class="card">
                <h3>Feedback</h3>
                <p> Feedbacks of all the users.</p>
                <button onclick="location.href='view_feedback.php'">Go to Feedback</button>
            </div>
        </div>

        <footer>
            <p>&copy; 2024 The SafePad. All Rights Reserved.</p>
        </footer>
    </div>
</body>
</html>