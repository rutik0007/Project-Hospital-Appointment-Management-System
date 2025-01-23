<?php
session_start();
require 'config.php';

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    echo "<script>alert('Please log in as a doctor to view appointments.'); window.location.href='doctor-login.html';</script>";
    exit();
}

// Fetch the logged-in doctor's appointments
$doctor_id = $_SESSION['doctor_id'];

$stmt = $conn->prepare("
    SELECT register.`Name` AS PatientName, appointments.`Date`, appointments.`Time`, appointments.`Specialization`, appointments.`Doctor`
    FROM `appointments`
    JOIN `register` ON appointments.`Patient ID` = register.`Patient ID`
    JOIN `doctor` ON appointments.`Doctor` = doctor.`Name`
    WHERE doctor.`Doctor ID` = ?
");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="style1.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Welcome, Doctor</h1>
    </header>
    <main class="container my-5">
        <h2>Your Appointments</h2>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['PatientName']); ?></td>
                            <td><?php echo htmlspecialchars($row['Date']); ?></td>
                            <td><?php echo htmlspecialchars($row['Time']); ?></td>
                            <td>
                                <!-- Reschedule Button -->
                                <form action="reschedule-appointment.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="specialization" value="<?php echo htmlspecialchars($row['Specialization']); ?>">
                                    <input type="hidden" name="doctor" value="<?php echo htmlspecialchars($row['Doctor']); ?>">
                                    <input type="hidden" name="patient_name" value="<?php echo htmlspecialchars($row['PatientName']); ?>">
                                    <input type="hidden" name="original_date" value="<?php echo htmlspecialchars($row['Date']); ?>">
                                    <input type="hidden" name="original_time" value="<?php echo htmlspecialchars($row['Time']); ?>">
                                    <button type="submit" class="btn btn-warning">Reschedule</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Life Care Hospital. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
