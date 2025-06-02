<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'website_db');
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}

// Retrieve articles from the database
$query = "SELECT * FROM articles ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Display the articles
while ($row = mysqli_fetch_assoc($result)) {
   echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
   if (!empty($row['image'])) {
       echo "<div class='articleimg'><img src='" . htmlspecialchars($row['image']) . "' alt='Article Image'></div>";
   }
   echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
   echo "<p><em>Published on: " . htmlspecialchars($row['created_at']) . "</em></p>";
}
?>