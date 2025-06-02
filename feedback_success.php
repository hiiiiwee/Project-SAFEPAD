<!DOCTYPE html>
<html>
<head>
    <title>Feedback Submitted</title>
    <style>
         <style>
        /* Page styling */
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #e91e63; /* SafePad's pink color */
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .message {
            font-size: 20px;
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }

        .feedback-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #e91e63; /* SafePad's pink color */
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            transition: 0.3s;
        }

        .button:hover {
            background-color: #d81b60;
        }
    </style>
    </style>
</head>
<body>

<h2 class="message">✅ Your feedback has been submitted successfully!</h2>
<?php
// Get the article ID from the URL
$article_id = isset($_GET['article_id']) ? $_GET['article_id'] : null;
?>

<!-- Ensure the 'id' parameter is included in the link -->
<a href="showuser_article.php<?php echo $article_id ? '?id=' . $article_id : ''; ?>" class="button">Back to Article</a>



</body>
</html>
