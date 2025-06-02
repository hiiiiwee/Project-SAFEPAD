<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update if different
$password = ""; // Update if different
$dbname = "website_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch quizzes
$sql = "SELECT id, question, option1, option2, option3, option4, correct_answer FROM quizzes";
$result = $conn->query($sql);

$quizzes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row; // Store all quizzes in an array
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz</title>
    <link rel="stylesheet" href="quiz.css">

</head>
<body>
    <!-- Header Section -->
 <header id="navi" class="header">
        <div class="logo">
            <img src="./Image/Logo.png" alt="The SafePad Logo">
        </div>
        <nav class="nav">
            <a href="user_dashboard.php" class="nav-link">Home</a>
            <a href="view_user_article.php" class="nav-link">Articles</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </header>

    <div class="app">
        <h1>Simple Quiz</h1>
        <div class="quiz">
            <h2 id="question">Question goes here</h2>
            <div id="answer-buttons">
                <!-- Answer buttons will be dynamically created here -->
            </div>
            <button id="next-btn" style="display:none;">Next</button>
        </div>
    </div>

    <script>
        const quizzes = <?php echo json_encode($quizzes); ?>;
        let currentQuestionIndex = 0;
        let score = 0;

        const questionElement = document.getElementById("question");
        const answerButtonsElement = document.getElementById("answer-buttons");
        const nextButton = document.getElementById("next-btn");

        function startQuiz() {
            currentQuestionIndex = 0;
            score = 0;
            nextButton.style.display = "none";
            showQuestion();
        }

        function showQuestion() {
            const currentQuiz = quizzes[currentQuestionIndex];
            questionElement.innerHTML = `${currentQuestionIndex + 1}. ${currentQuiz.question}`;
            answerButtonsElement.innerHTML = "";

            for (let i = 1; i <= 4; i++) {
                const button = document.createElement("button");
                button.innerHTML = currentQuiz[`option${i}`];
                button.classList.add("btn");
                button.dataset.correct = currentQuiz.correct_answer == i; // Check if this option is correct
                button.addEventListener("click", selectAnswer);
                answerButtonsElement.appendChild(button);
            }
        }

        function selectAnswer(e) {
            const selectedButton = e.target;
            const correct = selectedButton.dataset.correct === "true";

            if (correct) {
                score++;
            }

            Array.from(answerButtonsElement.children).forEach(button => {
                button.classList.add(button.dataset.correct === "true" ? "correct" : "incorrect");
                button.disabled = true;
            });

            nextButton.style.display = "block";
        }

        function showScore() {
            questionElement.innerHTML = `You've scored ${score} out of ${quizzes.length}!`;
            answerButtonsElement.innerHTML = "";
            nextButton.innerHTML = "Restart Quiz";
            nextButton.style.display = "block";
            nextButton.addEventListener("click", startQuiz);
        }

        nextButton.addEventListener("click", () => {
            currentQuestionIndex++;
            if (currentQuestionIndex < quizzes.length) {
                showQuestion();
            } else {
                showScore();
            }
            nextButton.style.display = "none";
        });

        // Start the quiz
        startQuiz();
    </script>
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
