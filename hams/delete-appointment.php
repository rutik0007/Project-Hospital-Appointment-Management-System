<?php
session_start();
require 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to delete an appointment.'); window.location.href='login.html';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $specialization = $_POST['specialization'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Delete the appointment
    $stmt = $conn->prepare("DELETE FROM appointments WHERE `Patient ID` = ? AND `Specialization` = ? AND `Doctor` = ? AND `Date` = ? AND `Time` = ?");
    $stmt->bind_param("issss", $user_id, $specialization, $doctor, $date, $time);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment deleted successfully.'); window.location.href='view-appointments.php';</script>";
    } else {
        echo "<script>alert('Failed to delete appointment.'); window.location.href='view-appointments.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
