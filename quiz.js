let questions = [];
let currentQuestionIndex = 0;
let score = 0;

const questionElement = document.getElementById("question");
const answerButtonsElement = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn");

function startQuiz(data) {
    questions = data;
    currentQuestionIndex = 0;
    score = 0;
    nextButton.style.display = "none";
    showQuestion();
}

function showQuestion() {
    const currentQuestion = questions[currentQuestionIndex];
    questionElement.innerHTML = `${currentQuestionIndex + 1}. ${currentQuestion.question}`;
    answerButtonsElement.innerHTML = "";

    currentQuestion.answers.forEach(function (answer, index) {
        const button = document.createElement("button");
        button.innerHTML = answer.text;
        button.classList.add("btn");
        button.dataset.correct = answer.correct; // Assign data-correct as "true" or "false"
        button.addEventListener("click", selectAnswer);
        answerButtonsElement.appendChild(button);
    });
}

function selectAnswer(e) {
    const selectedButton = e.target;
    const correct = selectedButton.dataset.correct === "true"; // Compare correctly with a string
    if (correct) {
        score++;
    }
    Array.from(answerButtonsElement.children).forEach(function (button) {
        button.classList.add(button.dataset.correct === "true" ? "correct" : "incorrect");
        button.disabled = true; // Disable all buttons after an answer is selected
    });
    nextButton.style.display = "block";
}

function endQuiz() {
    questionElement.innerHTML = `You've scored ${score} out of ${questions.length}!`;
    answerButtonsElement.innerHTML = "";
    nextButton.style.display = "none";

    const restartButton = document.createElement("button");
    restartButton.innerHTML = "Restart Quiz";
    restartButton.classList.add("btn");
    restartButton.style.marginTop = "20px";
    restartButton.addEventListener("click", function() {
        startQuiz(questions);
    });
    answerButtonsElement.appendChild(restartButton);
}

nextButton.addEventListener("click", function() {
    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        showQuestion();
    } else {
        endQuiz();
    }
    nextButton.style.display = "none";
});

// Function to fetch quiz data using XMLHttpRequest
function loadQuizData() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_quizzes.php', true); // Load data from the backend
    xhr.onload = function () {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText); // Parse JSON response
            console.log(data); // Debugging
            startQuiz(data);
        } else {
            console.error('Error fetching quiz data:', xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Request failed');
    };
    xhr.send();
}

// Start the quiz loading process
loadQuizData();