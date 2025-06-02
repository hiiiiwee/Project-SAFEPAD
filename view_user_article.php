<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'website_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch articles
$query = "SELECT * FROM articles ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <style>
       /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Body Styling */
body {
    background-color: #f4f7f6;
    color: #333;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 40px;
    background-color: white;
    border-bottom: 3.5px solid #D70383;
}

.header .logo img {
    height: 50px;
}

.nav {
    display: flex;
    gap: 20px;
}

.nav-link {
    text-decoration: none;
    color: black;
    font-size: 18px;
    font-weight: bold;
    padding: 10px 16px;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #D70383;
}

/* Articles Section */
h2 {
    text-align: center;
    font-size: 28px;
    margin: 20px 0;
    font-weight: bold;
}

/* Articles Container */
.articles-container {
    display: flex;
    flex-wrap: wrap;  /* Allows articles to move to next row if needed */
    justify-content: space-evenly;  /* Centers content and ensures even spacing */
    gap: 20px;
    padding: 20px;
}

/* Article Cards */
.article-card {
    background-color: white;
    width: 300px;  /* Set fixed width for all cards */
    max-width: 300px;  /* Prevents cards from growing too large */
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 2px solid #D70383;
    text-align: center;
    flex: 1 1 0px; /* Allows flexibility */
}

/* Ensure cards are responsive */
@media (max-width: 768px) {
    .articles-container {
        flex-direction: column;  /* Stack the articles on smaller screens */
        align-items: center;     /* Center the content */
    }
    .article-card {
        width: 100%;  /* Make cards take full width on small screens */
        max-width: 90%;  /* Limit the maximum width */
    }
}


.article-card:hover {
    transform: translateY(-5px);
}

.article-card h2 {
    font-size: 20px;
    color: #D70383;
    margin-bottom: 10px;
}

.articleimg {
    width: 100%;
    height: 180px; /* Set a fixed height */
    overflow: hidden; /* Hides any excess image */
    border-radius: 5px;
}

.articleimg img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures images are clipped properly */
}

/* Article Description */
.article-card p {
    font-size: 14px;
    color: #333;
    margin: 10px 0;
    max-height: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Button Container */
.button-container {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
}

/* Buttons */
.button {
    text-decoration: none;
    font-size: 14px;
    padding: 10px 16px;
    border-radius: 6px;
    font-weight: bold;
    display: inline-block;
    transition: all 0.3s ease;
}

.view-button {
    background-color: #007bff;
    color: white;
}

.view-button:hover {
    background-color: #0056b3;
}

.feedback-button {
    background-color: #D70383;
    color: white;
}

.feedback-button:hover {
    background-color: #a00268;
}

/* Footer */
.footer {
    text-align: center;
    padding: 20px;
    background-color: white;
    color: #7f8c8d;
    font-size: 14px;
    margin-top: 20px;
    border-top: 3px solid #D70383;
}


    </style>
</head>
<body>
<header id="navi" class="header">
    <div class="logo">
        <img src="./Image/Logo.png" alt="The SafePad Logo">
    </div>
    <nav class="nav">
        <a href="user_dashboard.php" class="nav-link">Home</a>
        <a href="view_user_article.php" class="nav-link">Articles</a>
        <a href="quiz.php" class="nav-link">Quiz</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </nav>
</header>
<h2>Articles</h2>

<div class="articles-container">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="article-card">
            <h2><?= htmlspecialchars($row['title']) ?></h2>
            <?php if (!empty($row['image'])) { ?>
                <div class="articleimg">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Article Image">
                </div>
            <?php } ?>
            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
            <div class="button-container">
                <a href="showuser_article.php?id=<?= $row['id'] ?>" class="button view-button">View Article</a>
                <a href="showuser_article.php?id=<?= $row['id'] ?>" class="button feedback-button">Feedback</a>
            </div>
        </div>
    <?php } ?>
</div>
<footer id="footer" class="footer">
    <div class="footer-content">
        <div class="contact">
            <h3>Contact</h3>
            <p>📞 +977 9804857458</p>
            <p>📧 the_safepad@gmail.com</p>
        </div>
        <div class="about">
            <h3>About</h3>
            <p>Works on public awareness/education, policy advocacy,<br> innovation and research around dignified menstruation<br> and menstrual health and hygiene</p>
        </div>
    </div>
    <p class="copyright">© 2024 SafePad. All rights reserved</p>
</footer>
</body>
</html>
