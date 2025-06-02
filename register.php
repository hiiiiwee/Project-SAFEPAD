<?php 
$conn = mysqli_connect('localhost', 'root', '', 'website_db');

if (isset($_POST['signup'])) {
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];

    // Check if email exists in admin table
    $checkAdmin = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $checkAdmin->bind_param("s", $email);
    $checkAdmin->execute();
    $resultAdmin = $checkAdmin->get_result();

    if ($resultAdmin->num_rows > 0) {
        echo "This email is already used by an admin.";
        exit();
    }

    if ($cpass === $pass) {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT); // Secure password storage
        
        // Correct SQL Query (without role)
        $sql = "INSERT INTO `users`(`username`, `password`, `email`) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fname, $hashed_pass, $email);
        
        if ($stmt->execute()) {
            header('Location: ../index.php');
            exit();
        } else {
            echo "Error: Could not register user.";
        }
    } else {
        echo "Error: Passwords do not match.";
    }
    if (!preg_match("/^[A-Za-z\s]+$/", $fname)) {
        echo "Error: Name should only contain letters and spaces.";
        exit();
    }
}
?>
