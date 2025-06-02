<?php
session_start();

// Only allow admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'website_db');

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Fetch all feedback with user details
$sql = "SELECT f.id, u.username, f.article_id, f.feedback, f.created_at
        FROM feedback f
        JOIN users u ON f.user_id = u.id
        ORDER BY f.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="styles.css"> <!-- Admin CSS -->
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
    padding: 15px 40px;
    background-color: #fff;
    color: rgb(14, 13, 13);
    border-bottom: 3.5px solid #D70383;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.header .logo img {
    height: 55px;
}

.header .nav {
    display: flex;
    gap: 20px;
}

.header .nav-link {
    color: black;
    text-decoration: none;
    font-size: 18px;
    padding: 10px 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
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
/* Feedback Page Styling */
.feedback-container {
    width: 90%;
    max-width: 1000px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #D70383;
    margin-bottom: 20px;
    font-size: 28px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background: #D70383;
    color: white;
    font-size: 18px;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

tr:hover {
    background: #f1f1f1;
}

/* Responsive Table */
 @media (max-width: 768px) {
    table, thead, tbody, th, td, tr {
        display: block;
        }

        th {
            display: none;
        }

        td {
            position: relative;
            padding-left: 50%;
            text-align: right;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            font-weight: bold;
            text-align: left;
            color: #D70383;
        }
}
/* Footer */
footer {
    text-align: center;
    padding: 15px;
    background-color: #fff;
    color: #7f8c8d;
    font-size: 14px;
    border-top: 2px solid #D70383;
    box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
    margin-top: auto;
}
    </style>
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
            <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
            <a href="manage_quizzes.php" class="nav-link">Manage Quizzes</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </header>

<main class="main-content">
    <div class="feedback-container">
        <h2>User Feedback</h2>

        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Article ID</th>
                    <th>Feedback</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td data-label="User"><?= htmlspecialchars($row['username']); ?></td>
                        <td data-label="Article ID"><?= htmlspecialchars($row['article_id']); ?></td>
                        <td data-label="Feedback"><?= htmlspecialchars($row['feedback']); ?></td>
                        <td data-label="Submitted At"><?= htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Footer Section -->
<footer>
        &copy; 2024 Your Website. All rights reserved.
    </footer>

</body>
</html>

<?php
$conn->close();
?>
