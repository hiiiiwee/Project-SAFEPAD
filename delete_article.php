<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'website_db');
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}

// Handle delete request
if (isset($_POST['delete'])) {
   $article_id = $_POST['article_id'];
   $delete_query = "DELETE FROM articles WHERE id = '$article_id'";
   if (mysqli_query($conn, $delete_query)) {
       header("Location: view_articles.php");
       exit;
   } else {
       echo "Error deleting article: " . mysqli_error($conn);
   }
}
?>