<?php
/* File: login.php */

session_start(); // Start the session
require 'config.php'; // Database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email or password is empty
    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required.'); window.location.href='login.html';</script>";
        exit();
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT `Patient ID`, `Password` FROM `register` WHERE `Email` = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['Patient ID'];
            $_SESSION['logged_in'] = true; // Flag to check if the user is logged in

            // Redirect to homepage
            header('Location: index.html');
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email. Please register.'); window.location.href='register.html';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request method.'); window.location.href='login.html';</script>";
}

$conn->close();
?>
