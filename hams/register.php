<?php
/* File: register.php */
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $patient_id = $_POST['number'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO `register` (`Patient ID`, `Name`, `Email`, `Gender`, `Password`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $patient_id, $name, $email, $gender, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please login.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error: Unable to register. Please try again.'); window.location.href='register.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
