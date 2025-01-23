<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if the doctor exists
    $stmt = $conn->prepare("SELECT `Doctor ID`, `Password` FROM `doctor` WHERE `Email` = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $doctor = $result->fetch_assoc();

        // Compare plain text password
        if ($password === $doctor['Password']) { // Direct comparison
            $_SESSION['doctor_id'] = $doctor['Doctor ID'];
            header('Location: doctor-dashboard.php'); // Redirect to doctor dashboard
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='doctor-login.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email.'); window.location.href='doctor-login.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
