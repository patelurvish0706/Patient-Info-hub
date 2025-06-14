<?php
session_start();
include 'db_connection.php'; // Make sure this path is correct

if (!isset($_SESSION['admin_Id']) || !isset($_SESSION['hospital_Id'])) {
    die("Access denied. Admin or Hospital not logged in.");
}

$adminId = $_SESSION['admin_Id'];
$hospitalId = $_SESSION['hospital_Id'];

// Get and sanitize form inputs
$name = trim($_POST['department_name']);
$email = trim($_POST['department_email']);
$phone = trim($_POST['department_phone']);
$password = trim($_POST['department_password']);
$description = trim($_POST['department_description']);

// Check if all fields are filled
if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($description)) {
    echo "<script>alert('Please fill all fields.'); window.history.back();</script>";
    exit;
}

// Hash the password
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$hashedPassword = $password;

// Insert into database
$stmt = $conn->prepare("INSERT INTO departments (hospital_Id, admin_Id, dept_Name, dept_Email, dept_Phone, dept_Password, dept_Description)
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisssss", $hospitalId, $adminId, $name, $email, $phone, $hashedPassword, $description);

if ($stmt->execute()) {
    echo "<script>window.history.back();</script>";
} else {
    echo "<script>alert('Failed to add department: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
