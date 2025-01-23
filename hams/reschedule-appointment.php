<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get original and new appointment details
    $specialization = $_POST['specialization'];
    $doctor = $_POST['doctor'];
    $patient_name = $_POST['patient_name'];
    $original_date = $_POST['original_date'];
    $original_time = $_POST['original_time'];

    if (isset($_POST['new_date']) && isset($_POST['new_time'])) {
        // New date and time provided for rescheduling
        $new_date = $_POST['new_date'];
        $new_time = $_POST['new_time'];

        $stmt = $conn->prepare("
            UPDATE `appointments`
            SET `Date` = ?, `Time` = ?
            WHERE `Specialization` = ? AND `Doctor` = ? AND `Date` = ? AND `Time` = ?
        ");
        $stmt->bind_param('ssssss', $new_date, $new_time, $specialization, $doctor, $original_date, $original_time);

        if ($stmt->execute()) {
            echo "<script>alert('Appointment rescheduled successfully.'); window.location.href='doctor-dashboard.php';</script>";
        } else {
            echo "<script>alert('Error rescheduling appointment: " . $stmt->error . "'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        // Show reschedule form if new date and time are not set yet
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><b>Reschedule Appointment</b></title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="style.css">
            <!-- Custom CSS for Styling -->
            <style>
                body {
                    background-color: #f0f8f4;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                .container {
                    max-width: 600px;
                    padding: 40px;
                    background-color: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    margin-top: 100px;
                }

                h2 {
                    text-align: center;
                    margin-bottom: 30px;
                    font-size: 2rem;
                    color: #28a745; /* Green color */
                }

                .form-control, .btn {
                    border-radius: 25px;
                    font-size: 1rem;
                }

                .form-label {
                    font-weight: bold;
                    color: #333;
                }

                .btn-primary {
                    background-color: #28a745; /* Green color */
                    border: none;
                    padding: 12px;
                    font-size: 1rem;
                    font-weight: bold;
                    width: 100%;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }

                .btn-primary:hover {
                    background-color: #218838; /* Darker green on hover */
                }

                .alert {
                    text-align: center;
                    color: #d9534f;
                    font-weight: bold;
                }

                .form-group {
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Reschedule Appointment</h2>
                <form action="reschedule-appointment.php" method="POST">
                    <!-- Hidden inputs for passed data -->
                    <input type="hidden" name="specialization" value="<?php echo htmlspecialchars($specialization); ?>">
                    <input type="hidden" name="doctor" value="<?php echo htmlspecialchars($doctor); ?>">
                    <input type="hidden" name="patient_name" value="<?php echo htmlspecialchars($patient_name); ?>">
                    <input type="hidden" name="original_date" value="<?php echo htmlspecialchars($original_date); ?>">
                    <input type="hidden" name="original_time" value="<?php echo htmlspecialchars($original_time); ?>">

                    <!-- New Date Field -->
                    <div class="form-group">
                        <label for="new_date" class="form-label">New Date</label>
                        <input type="date" id="new_date" name="new_date" class="form-control" required>
                    </div>

                    <!-- New Time Field -->
                    <div class="form-group">
                        <label for="new_time" class="form-label">New Time</label>
                        <input type="time" id="new_time" name="new_time" class="form-control" required>
                    </div>

                    <!-- Reschedule Button -->
                    <button type="submit" class="btn btn-primary">Reschedule Appointment</button>
                </form>
            </div>

            <!-- Bootstrap JS (optional) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='doctor-dashboard.php';</script>";
}
?>
