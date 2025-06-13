<?php
session_start();
include 'db_connection.php'; // Adjust path if needed

// Sanitize input
$email = trim($_POST['login-deprt-email']);
$password = trim($_POST['login-deprt-password']);

// Check if department exists
$stmt = $conn->prepare("SELECT dept_Id, dept_Name, dept_Password FROM departments WHERE dept_Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $dept = $result->fetch_assoc();

    // Since password is stored as plain text
    if ($password === $dept['dept_Password']) {
        // Set session variables
        $_SESSION['dept_Id'] = $dept['dept_Id'];
        $_SESSION['dept_Name'] = $dept['dept_Name'];

        echo "<script>window.location.href = '../dashboard_department.php';</script>";
    } else {
        echo "<script>alert('Incorrect password.'); history.back();</script>";
    }
} else {
    echo "<script>alert('No department found with this email.'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>
