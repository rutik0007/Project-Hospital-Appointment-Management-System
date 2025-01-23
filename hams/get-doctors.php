<?php
require 'config.php';

if (isset($_GET['specialization'])) {
    $specialization = $_GET['specialization'];

    $stmt = $conn->prepare("SELECT `Name` FROM `doctor` WHERE `Specialization` = ?");
    $stmt->bind_param("s", $specialization);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = ['name' => $row['Name']];
    }

    echo json_encode($doctors);
}

$stmt->close();
$conn->close();
?>
