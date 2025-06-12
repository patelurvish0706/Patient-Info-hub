<?php
session_start();
include 'db_connection.php'; // Adjust path

if (!isset($_SESSION['admin_Id']) || !isset($_SESSION['hospital_Id'])) {
    die("Unauthorized access.");
}

$adminId    = $_SESSION['admin_Id'];
$hospitalId = $_SESSION['hospital_Id'];

/* Capture and sanitize form data */
$name        = trim($_POST['doctor_name']);
$email       = trim($_POST['doctor_email']);
$phone       = trim($_POST['doctor_phone']);
$passwordRaw = trim($_POST['doctor_password']);
$deptId      = intval($_POST['doctor_department']);
$specialist  = trim($_POST['doctor_description']);

/* Basic validation */
if ($name === "" || $email === "" || $phone === "" || $passwordRaw === "" || $deptId <= 0 || $specialist === "") {
    echo "<script>alert('All fields are required.'); history.back();</script>";
    exit;
}

/* Check if department exists and belongs to admin */
$check = $conn->prepare("SELECT dept_Id FROM departments WHERE dept_Id = ? AND admin_Id = ?");
$check->bind_param("ii", $deptId, $adminId);
$check->execute();
$res = $check->get_result();

if ($res->num_rows === 0) {
    echo "<script>alert('Invalid department selected.'); history.back();</script>";
    exit;
}

/* Hash password */
// $hashedPassword = password_hash($passwordRaw, PASSWORD_DEFAULT);
$hashedPassword = $passwordRaw ;

/* Insert doctor */
$insert = $conn->prepare(
    "INSERT INTO doctors (dept_Id, hospital_Id, admin_Id, doct_Email, doct_Password, doct_Name, doct_Phone, doct_Speacialist)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);
$insert->bind_param("iiisssss", $deptId, $hospitalId, $adminId, $email, $hashedPassword, $name, $phone, $specialist);

if ($insert->execute()) {
    echo "<script>window.history.back();</script>";
} else {
    echo "<script>alert('Error: " . $insert->error . "'); history.back();</script>";
}
