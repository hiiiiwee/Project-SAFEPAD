<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'user') {
    header('Location: login.html'); // Redirect to login if unauthorized
    exit;
}

// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'website_db');

// Fetch user details
$uid = $_SESSION['uid'];
$query = "SELECT * FROM `users` WHERE `id` = $uid";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Error fetching user data!";
    exit;
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The SafePad - User Dashboard</title>
    <link rel="icon" type="image/png" href="../Image/Logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Khula:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Montserrat', sans-serif;
    background-color: #C8B8D6; 
    margin: 0;
    padding: 0;
    color: #000;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: white;
    padding: 10px 20px;
    border-bottom: 3.5px solid #D70383;
    position: sticky;
    top: 0; 
    z-index: 1000;
}

.logo img {
    height: 50px;
    width: auto;
    object-fit: contain;
}

.header .logo span {
    color: #f06292;
}

.nav {
    display: flex;
    gap: 35px;
}

.nav a {
    font-family: 'Montserrat', sans-serif;
    font-weight: normal;
    font-size: 1.3rem;
}

.nav-link {
    text-decoration: none;
    color: black;
    font-family: 'Montserrat', sans-serif;
    font-weight: bold;
    font-size: 1.2rem;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #D70383;
}

/* Image Section */
.image-section {
    position: relative;
    width: 100%;
    height: 100vh;
    overflow: hidden;
    margin-bottom: 0;
}

.main-image {
    width: 100%;
    height: 91%;
    object-fit: cover; 
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding-left: 50px;
    color: #fff;
    text-align: left;
}

.overlay h1 {
    font-family: 'Kaushan Script', cursive;
    font-size: 4.5rem;
    margin-bottom: 15px;
    line-height: 1.2;
}

.overlay p {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.5rem;
    line-height: 1.2;
}

        /* Articles Section */
        .articles-section {
            padding: 40px 60px;
            background-color: #f4f7f6;
        }

        .articles-section h2 {
            text-align: center;
            color: #D70383;
            margin-bottom: 20px;
        }

        .articles-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .article {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background-color: #fff;
        }

        .article h2 {
            font-size: 20px;
            color: #D70383;
        }

        .article img {
            max-width: 100%;
            border-radius: 5px;
            margin: 10px 0;
        }

        .article p {
            font-size: 16px;
            color: #34495e;
        }

        .article a {
            display: inline-block;
            margin-top: 10px;
            color: #fff;
            background-color: #D70383;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .article a:hover {
            background-color: #d537f5;
        }

        /* Quiz Section */
    .quiz-section {
        padding: 40px 20px;
        background-color: #fff;
        text-align: center;
        border-top: 0px solid #D70383;
    }

    .quiz-section p {
        font-size: 40px;
        color: #34495e;
        margin-bottom: 20px;
    }

    .quiz-button {
        display: inline-block;
        background-color: #D70383;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .quiz-button:hover {
        background-color: #d537f5;
    }
    /* Footer Section */
.footer {
    background-color: #333; 
    color: #fff;
    padding: 40px 0px; 
    text-align: left;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start; 
    max-width: 1200px; 
    margin: 0 auto;
}

.contact {
    font-family: 'Montserrat', sans-serif; 
    font-size: 2rem; 
}

.contact p {
    font-size: 1rem;
}
.footer h3 {
    font-family: 'Montserrat', sans-serif;
    font-size: 2rem;
    margin-bottom: 10px;
}

.about {
    font-family: 'Montserrat', sans-serif; 
    font-size: 1rem; 
}

.about h3 {
    text-decoration: underline;
    text-underline-offset: 10px;
}

.footer p {
    font-family: 'Montserrat', sans-serif;
    font-size: rem;
    line-height: 1.5;
    margin: 0;
}


.copyright {
    font-family: 'Montserrat', sans-serif;
    text-align: center;
    padding-top: 1%;
    font-size: 25px; 
    opacity: 0.8; 
}
/* Footer Styling */
.footer {
    position: relative; /* Enables roll-up effect */
    bottom: 0;
    background-color: #333; /* Footer background color */
    color: #fff; /* Text color */
    padding: 20px 0;
    text-align: center;
    z-index: 1000;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    max-width: 1200px;
    margin: 0 auto;
    padding: 10px 20px;
    gap: 20px;
}

.contact, .about {
    flex: 1;
    text-align: left;
}

.contact h3, .about h3 {
    font-size: 18px;
    margin-bottom: 10px;
    text-transform: uppercase;
    color: #ffcc00; /* Highlighted color for headings */
}

.contact p, .about p {
    font-size: 14px;
    line-height: 1.6;
    margin: 5px 0;
}

.contact p a {
    color: #ffcc00;
    text-decoration: none;
}

.contact p a:hover {
    text-decoration: underline;
}

.about p {
    text-align: justify;
}

.copyright {
    margin-top: 20px;
    font-size: 12px;
    color: #aaa;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .contact, .about {
        flex: unset;
        margin-bottom: 20px;
    }

    .about p {
        text-align: center;
    }
}

.articleimg{
        height: 200px;
        width: 250px;
        margin:10px 0;
       }
       .articleimg img{
        height:100%;
        width: 100%;
        object-fit: cover;
        object-position: center;
       }

    </style>
</head>
<body>

<header id="navi" class="header">
    <div class="logo">
        <img src="./Image/Logo.png" alt="The SafePad Logo">
    </div>
    <nav class="nav">
        <a href="#body" class="nav-link">Home</a>
        <a href="view_user_article.php" class="nav-link">Articles</a>
        <a href="#quiz" class="nav-link">Quiz</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </nav>
</header>

<section id="body" class="image-section">
    <img src="./Image 2.jpg" alt="Awareness Image" class="main-image">
    <div class="overlay">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p>Creating Awareness, Empowering Health, Inspiring Change</p>
    </div>
</section>

<section id="articles" class="articles-section">
    <h2>Articles</h2>
    <div class="articles-container">
        <?php
        $query = "SELECT * FROM articles ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='article'>";
            echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
            if (!empty($row['image'])) {
                echo "<div class = 'articleimg'><img src='" . htmlspecialchars($row['image']) . "' alt='Article Image'></div>";
            }
            echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
            echo "<a href='showuser_article.php?id=" . $row['id'] . "'>View Article</a> <emp>";
            echo "<a href='showuser_article.php?id=" . $row['id'] . "' class='feedback-button'>Feedback</a>";
            echo "</div>";
        }
        ?>
    </div>
</section>

<section id="quiz" class="quiz-section">
    <p>Test Your Knowledge - Take a Quiz!</p>
    <a href="quiz.php" class="quiz-button">Click Here</a>
</section>

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
