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

// Handle Add/Edit/Delete operations for quizzes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $question = $conn->real_escape_string($_POST['question']);
        $option1 = $conn->real_escape_string($_POST['option1']);
        $option2 = $conn->real_escape_string($_POST['option2']);
        $option3 = $conn->real_escape_string($_POST['option3']);
        $option4 = $conn->real_escape_string($_POST['option4']);
        $correct_answer = $conn->real_escape_string($_POST['correct_answer']);

        // Insert quiz question into the database
        $stmt = $conn->prepare("INSERT INTO quizzes (question, option1, option2, option3, option4, correct_answer) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $question, $option1, $option2, $option3, $option4, $correct_answer);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $question = $conn->real_escape_string($_POST['question']);
        $option1 = $conn->real_escape_string($_POST['option1']);
        $option2 = $conn->real_escape_string($_POST['option2']);
        $option3 = $conn->real_escape_string($_POST['option3']);
        $option4 = $conn->real_escape_string($_POST['option4']);
        $correct_answer = $conn->real_escape_string($_POST['correct_answer']);

        // Update quiz question in the database
        $stmt = $conn->prepare("UPDATE quizzes SET question = ?, option1 = ?, option2 = ?, option3 = ?, option4 = ?, correct_answer = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $question, $option1, $option2, $option3, $option4, $correct_answer, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Delete quiz question from the database
        $stmt = $conn->prepare("DELETE FROM quizzes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all quizzes
$quizzes_result = $conn->query("SELECT * FROM quizzes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes - Admin</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <style>
        /* Form Styling */
.add-quiz-form, .quiz-list {
    background: #ffffff;
    padding: 20px;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    max-width: 800px;
}

.add-quiz-form h2, .quiz-list h2 {
    font-size: 24px;
    font-weight: bold;
    color: #001e4d;
    margin-bottom: 15px;
    text-align: center;
}

/* Input Fields */
.add-quiz-form input[type="text"], 
.add-quiz-form select,
form input[type="text"], 
form select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    color: #333;
}

.add-quiz-form label, form label {
    font-size: 16px;
    color: #001e4d;
    font-weight: 600;
}

/* Buttons */
.add-quiz-form button,
form button {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-quiz-form button[name="add"] {
    background-color: #007bff; /* Blue for Add Button */
    color: white;
}

.add-quiz-form button[name="add"]:hover {
    background-color: #0056b3;
}

form button[name="edit"] {
    background-color: #28a745; /* Green for Edit Button */
    color: white;
}

form button[name="edit"]:hover {
    background-color: #218838;
}

form button[name="delete"] {
    background-color: #dc3545; /* Red for Delete Button */
    color: white;
}

form button[name="delete"]:hover {
    background-color: #c82333;
}

/* Table Styling */
.quiz-list table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.quiz-list table th, 
.quiz-list table td {
    padding: 12px 15px;
    text-align: left;
    border: 1px solid #ddd;
    font-size: 14px;
    color: #333;
}

.quiz-list table th {
    background-color: #f8f9fa;
    font-weight: bold;
    color: #001e4d;
}

.quiz-list table tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Footer */
footer {
    text-align: center;
    padding: 15px 0;
    margin-top: 20px;
    font-size: 14px;
    color: #555;
    border-top: 1px solid #ddd;
}

/* Responsive Design */
@media (max-width: 768px) {
    .add-quiz-form, .quiz-list {
        padding: 15px;
    }

    .add-quiz-form input[type="text"],
    form input[type="text"],
    form select {
        font-size: 12px;
    }

    .add-quiz-form button,
    form button {
        font-size: 14px;
    }

    .quiz-list table th,
    .quiz-list table td {
        font-size: 12px;
        padding: 10px;
    }
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
            <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
            <a href="view_articles.php" class="nav-link">Articles</a>
            <a href="manage_quizzes.php" class="nav-link">Manage Quizzes</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Manage Quizzes</h1>
            <p>Add, edit, or delete quiz questions for users.</p>
        </header>

        <!-- Add New Quiz -->
        <div class="add-quiz-form">
            <h2>Add New Quiz Question</h2>
            <form method="POST">
                <label for="question">Question:</label>
                <input type="text" id="question" name="question" required>
                <label for="option1">Option 1:</label>
                <input type="text" id="option1" name="option1" required>
                <label for="option2">Option 2:</label>
                <input type="text" id="option2" name="option2" required>
                <label for="option3">Option 3:</label>
                <input type="text" id="option3" name="option3" required>
                <label for="option4">Option 4:</label>
                <input type="text" id="option4" name="option4" required>
                <label for="correct_answer">Correct Answer:</label>
                <select id="correct_answer" name="correct_answer" required>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
                <button type="submit" name="add">Add Quiz</button>
            </form>
        </div>

        <!-- Existing Quizzes List -->
        <div class="quiz-list">
            <h2>Existing Quizzes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Option 1</th>
                        <th>Option 2</th>
                        <th>Option 3</th>
                        <th>Option 4</th>
                        <th>Correct Answer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($quiz = $quizzes_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $quiz['question']; ?></td>
                            <td><?php echo $quiz['option1']; ?></td>
                            <td><?php echo $quiz['option2']; ?></td>
                            <td><?php echo $quiz['option3']; ?></td>
                            <td><?php echo $quiz['option4']; ?></td>
                            <td><?php echo $quiz['correct_answer']; ?></td>
                            <td>
                                <!-- Edit Form -->
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $quiz['id']; ?>">
                                    <input type="text" name="question" value="<?php echo $quiz['question']; ?>" required>
                                    <input type="text" name="option1" value="<?php echo $quiz['option1']; ?>" required>
                                    <input type="text" name="option2" value="<?php echo $quiz['option2']; ?>" required>
                                    <input type="text" name="option3" value="<?php echo $quiz['option3']; ?>" required>
                                    <input type="text" name="option4" value="<?php echo $quiz['option4']; ?>" required>
                                    <select name="correct_answer" required>
                                        <option value="1" <?php if ($quiz['correct_answer'] == 1) echo 'selected'; ?>>Option 1</option>
                                        <option value="2" <?php if ($quiz['correct_answer'] == 2) echo 'selected'; ?>>Option 2</option>
                                        <option value="3" <?php if ($quiz['correct_answer'] == 3) echo 'selected'; ?>>Option 3</option>
                                        <option value="4" <?php if ($quiz['correct_answer'] == 4) echo 'selected'; ?>>Option 4</option>
                                    </select>
                                    <button type="submit" name="edit">Edit</button>
                                </form>
                                <!-- Delete Form -->
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $quiz['id']; ?>">
                                    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <footer>
            <p>&copy; 2024 The SafePad. All Rights Reserved.</p>
        </footer>
    </div>
</body>
</html>