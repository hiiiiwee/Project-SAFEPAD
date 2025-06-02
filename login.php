<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'website_db');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
// Fetch user based on email from admins or users
$sql_admin = "SELECT * FROM `admins` WHERE `email` = '$email'";
$result_admin = mysqli_query($conn, $sql_admin);

if ($result_admin && mysqli_num_rows($result_admin) > 0) {
    $row = mysqli_fetch_assoc($result_admin);

    // Verify password for admin
    if (password_verify($pass, $row['password'])) {
        $_SESSION['uid'] = $row['id'];
        $_SESSION['uname'] = $row['email'];
        $_SESSION['role'] = 'admin';

        // Redirect to admin dashboard
        header('Location:../admin_dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid Password for Admin');</script>";
    }
} else {
    // Check users table if not an admin
    $sql_user = "SELECT * FROM `users` WHERE `email` = '$email'";
    $result_user = mysqli_query($conn, $sql_user);

    if ($result_user && mysqli_num_rows($result_user) > 0) {
        $row = mysqli_fetch_assoc($result_user);

        // Verify password for user
        if (password_verify($pass, $row['password'])) {
            $_SESSION['uid'] = $row['id'];
            $_SESSION['uname'] = $row['username'];
            $_SESSION['role'] = 'user';

            // Redirect to user dashboard
            header('Location: ../user_dashboard.php');
            exit;
        } else {
            echo "<script>alert('Invalid Password for User');</script>";
        }
    } else {
        echo "<script>alert('User does not exist');</script>";
    }
}
}
?>