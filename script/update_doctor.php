<?php
session_start();
include 'db_connection.php'; // Adjust path

if (!isset($_SESSION['admin_Id']) || !isset($_SESSION['hospital_Id'])) {
    die("Unauthorized access.");
}

$adminId    = $_SESSION['admin_Id'];
$hospitalId = $_SESSION['hospital_Id'];

$doctId     = intval($_POST['doct_id']);
$name       = trim($_POST['doctor_name']);
$email      = trim($_POST['doctor_email']);
$phone      = trim($_POST['doctor_phone']);
$password   = trim($_POST['doctor_password']);
$deptId     = intval($_POST['doctor_department']);
$specialist = trim($_POST['doctor_description']);

// Check if doctor belongs to this admin
$checkStmt = $conn->prepare("SELECT doct_Id FROM doctors WHERE doct_Id = ? AND admin_Id = ?");
$checkStmt->bind_param("ii", $doctId, $adminId);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid doctor or permission denied.");
}

// Build update query
if ($password !== "") {
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedPassword = $password ;

    $stmt = $conn->prepare(
        "UPDATE doctors SET doct_Name = ?, doct_Email = ?, doct_Phone = ?, doct_Password = ?, dept_Id = ?, doct_Speacialist = ?
         WHERE doct_Id = ?"
    );
    $stmt->bind_param("ssssisi", $name, $email, $phone, $hashedPassword, $deptId, $specialist, $doctId);
} else {
    $stmt = $conn->prepare(
        "UPDATE doctors SET doct_Name = ?, doct_Email = ?, doct_Phone = ?, dept_Id = ?, doct_Speacialist = ?
         WHERE doct_Id = ?"
    );
    $stmt->bind_param("sssisi", $name, $email, $phone, $deptId, $specialist, $doctId);
}

if ($stmt->execute()) {
    echo "<script>window.history.back()</script>";
} else {
    echo "<script>alert('Error updating doctor.'); history.back();</script>";
}
