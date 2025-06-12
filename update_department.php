<?php
session_start();
include 'script/db_connection.php';      // adjust path

/* ─────── SECURITY ─────── */
if (!isset($_SESSION['admin_Id']) || !isset($_SESSION['hospital_Id'])) {
    die("Unauthorized.");
}
$adminId    = $_SESSION['admin_Id'];
$hospitalId = $_SESSION['hospital_Id'];

/* ─────── GET POST VALUES ─────── */
$deptId      = intval($_POST['dept_id']);
$name        = trim($_POST['dept_name']);
$email       = trim($_POST['dept_email']);
$phone       = trim($_POST['dept_phone']);
$passRaw     = trim($_POST['dept_password']);   // may be blank
$description = trim($_POST['dept_description']);

/* ─────── SIMPLE VALIDATION ─────── */
if ($deptId <= 0 || $name === '' || $email === '' || $phone === '' || $description === '') {
    echo "<script>alert('All fields (except password) are required.'); history.back();</script>";
    exit;
}

/* ─────── CONFIRM RECORD BELONGS TO THIS ADMIN/HOSPITAL ─────── */
$check = $conn->prepare("SELECT dept_Password FROM departments
                         WHERE dept_Id = ? AND hospital_Id = ? AND admin_Id = ?");
$check->bind_param("iii", $deptId, $hospitalId, $adminId);
$check->execute();
$res = $check->get_result();
if ($res->num_rows === 0) {
    die("Record not found or access denied.");
}
$current = $res->fetch_assoc();

/* ─────── HANDLE PASSWORD ─────── */
if ($passRaw !== '') {
    $hashed = $passRaw;
} else {
    $hashed = $current['dept_Password'];     // keep existing hash
}

/* ─────── UPDATE STATEMENT ─────── */
$update = $conn->prepare(
    "UPDATE departments
     SET dept_Name = ?, dept_Email = ?, dept_Phone = ?, dept_Password = ?, dept_Description = ?
     WHERE dept_Id = ?"
);
$update->bind_param("sssssi", $name, $email, $phone, $hashed, $description, $deptId);

if ($update->execute()) {
    // echo "<script>alert('Department updated.'); window.history.back();";
    echo "<script>  window.history.back();</script>";
} else {
    echo "<script>alert('Update failed: ". $update->error ."'); history.back();</script>";
}

$update->close();
$conn->close();
?>
