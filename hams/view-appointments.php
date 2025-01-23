<?php
session_start();
require 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your appointments.'); window.location.href='login.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch appointments for the logged-in user
$stmt = $conn->prepare("SELECT * FROM appointments WHERE `Patient ID` = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
    <style>
    /* Remove the outer glow effect */
    .btn {
        outline: none; /* Removes focus outline */
        box-shadow: none; /* Removes any shadow */
    }

    .btn:focus, .btn:active {
        outline: none; /* Ensure no outline on focus or active */
        box-shadow: none; /* Prevent glow when clicked */
    }
</style>
    
</head>
<body>
    <!-- Header -->
    <header class="bg-primary text-white text-center py-3">
        <h1>Your Appointments</h1>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <h2 class="mb-4">Appointments</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Specialization</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $specialization = htmlspecialchars($row['Specialization']);
                        $doctor = htmlspecialchars($row['Doctor']);
                        $date = htmlspecialchars($row['Date']);
                        $time = htmlspecialchars($row['Time']);
                        echo "<tr>
                                <td>$specialization</td>
                                <td>$doctor</td>
                                <td>$date</td>
                                <td>$time</td>
                                <td>
                                   <form action='delete-appointment.php' method='POST' style='display:inline;'>
    <input type='hidden' name='specialization' value='$specialization'>
    <input type='hidden' name='doctor' value='$doctor'>
    <input type='hidden' name='date' value='$date'>
    <input type='hidden' name='time' value='$time'>
    <button type='submit' class='btn btn-delete btn-sm' style='background-color: #81c784; border: none; font-size: 30px; padding: 5px 0px;10px; margin: 0; line-height: 1;'>Delete</button>
</form>



                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No appointments found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Life Care Hospital. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
