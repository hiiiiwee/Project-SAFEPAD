<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    die("You must be logged in to submit feedback.");
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'website_db');

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Ensure the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $article_id = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';
    $user_id = $_SESSION['uid'];

    // Validate inputs
    if ($article_id <= 0) {
        die("Invalid Article ID.");
    }
    if (empty($feedback)) {
        die("Feedback cannot be empty.");
    }

    // Prepare SQL statement to insert feedback
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, article_id, feedback, created_at) VALUES (?, ?, ?, NOW())");

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("iis", $user_id, $article_id, $feedback);

    if ($stmt->execute()) {
        // Redirect back to feedback_success.php WITH article_id
        header("Location: feedback_success.php?article_id=$article_id");
        exit();
    } else {
        die("Error submitting feedback: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("Invalid request method.");
}

// Close the database connection
$conn->close();
?>
