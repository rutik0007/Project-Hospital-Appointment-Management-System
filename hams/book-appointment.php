<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to book an appointment.'); window.location.href='login.html';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $specialization = $_POST['specialization'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    if (empty($specialization) || empty($doctor) || empty($date) || empty($time)) {
        echo "<script>alert('All fields are required.'); window.location.href='book-appointment.html';</script>";
        exit();
    }

    $check_user_stmt = $conn->prepare("SELECT * FROM appointments WHERE `Patient ID` = ?");
    $check_user_stmt->bind_param("i", $user_id);
    $check_user_stmt->execute();
    $existing_user_appointment = $check_user_stmt->get_result();
    if ($existing_user_appointment->num_rows > 0) {
        echo "<script>alert('You already have an appointment. Please cancel it to book a new one.'); window.location.href='book-appointment.html';</script>";
        $check_user_stmt->close();
        exit();
    }

    $check_doctor_stmt = $conn->prepare("SELECT * FROM appointments WHERE `Doctor` = ? AND `Date` = ? AND `Time` = ?");
    $check_doctor_stmt->bind_param("sss", $doctor, $date, $time);
    $check_doctor_stmt->execute();
    $existing_doctor_appointment = $check_doctor_stmt->get_result();
    if ($existing_doctor_appointment->num_rows > 0) {
        echo "<script>alert('This doctor is already booked for the selected date and time. Please choose a different time.'); window.location.href='book-appointment.html';</script>";
        $check_doctor_stmt->close();
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO appointments (`Patient ID`, `Specialization`, `Doctor`, `Date`, `Time`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $specialization, $doctor, $date, $time);
    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Error: Unable to book the appointment. Please try again.'); window.location.href='book-appointment.html';</script>";
    }

    $check_user_stmt->close();
    $check_doctor_stmt->close();
    $stmt->close();
}
$conn->close();
?>
