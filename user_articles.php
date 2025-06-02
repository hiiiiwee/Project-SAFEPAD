<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'user') {
    header('Location: login.html');
    exit;
}

$conn = mysqli_connect('localhost', 'root', '', 'website_db');

// Fetch all articles
$articles_query = "SELECT * FROM articles ORDER BY created_at DESC";
$articles_result = mysqli_query($conn, $articles_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    $article_id = $_POST['article_id'];
    $user_id = $_SESSION['uid'];

    $feedback_query = "INSERT INTO feedback (user_id, article_id, feedback) VALUES ('$user_id', '$article_id', '$feedback')";
    mysqli_query($conn, $feedback_query);
    echo "<script>alert('Feedback submitted!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
<header>
    <h1>Articles</h1>
</header>
<main>
    <?php while ($article = mysqli_fetch_assoc($articles_result)): ?>
        <div class="article">
            <h2><?php echo htmlspecialchars($article['title']); ?></h2>
            <p><?php echo htmlspecialchars($article['content']); ?></p>
            <p><small>By <?php echo htmlspecialchars($article['author']); ?> on <?php echo $article['created_at']; ?></small></p>
            
            <form method="POST">
                <textarea name="feedback" placeholder="Write your feedback here..." required></textarea>
                <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                <button type="submit">Submit Feedback</button>
            </form>
        </div>
    <?php endwhile; ?>
</main>
</body>
</html>
