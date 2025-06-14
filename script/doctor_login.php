<?php
session_start();
include 'db_connection.php'; // Adjust path if needed

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['login-doctor-email']);
    $password = $_POST['login-doctor-password'];

    // Query the doctor with this email
    $stmt = $conn->prepare("SELECT doct_Id, doct_Name, doct_Password FROM doctors WHERE doct_Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if doctor exists
    if ($result->num_rows === 1) {
        $doctor = $result->fetch_assoc();

        // Validate password
        if ($doctor['doct_Password'] === $password) { // No hash used as per your instruction
            $_SESSION['doct_Id'] = $doctor['doct_Id'];
            $_SESSION['doct_Name'] = $doctor['doct_Name'];

            echo "<script>window.location.href = '../dashboard_doctor.php';</script>";
        } else {
            echo "<script>alert('Incorrect password.'); history.back();</script>";
        }
    } else {
        echo "<script>alert('Doctor not found with this email.'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = '../index.php';</script>";
}
?>
