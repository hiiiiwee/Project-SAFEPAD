<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'website_db';

$conn = mysqli_connect('localhost', 'root', '', 'website_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch quizzes from the database
$sql = "SELECT * FROM quizzes";
$result = $conn->query($sql);

$quizzes = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $correctOption = isset($row['correct_option']) && in_array((int)$row['correct_option'], [1, 2, 3, 4])
            ? (int)$row['correct_option']
            : null;

        if ($correctOption === null) {
            error_log("Invalid correct_option for question ID: " . $row['id']);
            continue;
        }

        $quizzes[] = [
            'question' => $row['question'],
            'answers' => [
                ['text' => $row['option1'], 'correct' => $correctOption === 1],
                ['text' => $row['option2'], 'correct' => $correctOption === 2],
                ['text' => $row['option3'], 'correct' => $correctOption === 3],
                ['text' => $row['option4'], 'correct' => $correctOption === 4],
            ]
        ];
    }
} else {
    echo "No quizzes found";
    $conn->close();
    exit;
}

// Return quizzes in a custom tag format (not JSON)
echo "<quizzes>";
foreach ($quizzes as $quiz) {
    echo "<quiz>";
    echo "<question>" . htmlspecialchars($quiz['question']) . "</question>";
    foreach ($quiz['answers'] as $index => $answer) {
        echo "<option" . ($index + 1) . ">" . htmlspecialchars($answer['text']) . "</option" . ($index + 1) . ">";
    }
    echo "<correct_option>" . array_search(true, array_column($quiz['answers'], 'correct')) + 1 . "</correct_option>";
    echo "</quiz>";
}
echo "</quizzes>";

// Close connection
$conn->close();
?>

