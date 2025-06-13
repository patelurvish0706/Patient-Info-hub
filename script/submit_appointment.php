<?php
session_start();
include 'db_connection.php';

if (!isset($_POST['user_id'], $_POST['dept_id'], $_POST['app_date'], $_POST['app_time'], $_POST['visit_for'])) {
    echo "<script>alert('Missing appointment data.'); history.back();</script>";
    exit;
}

$userId   = $_POST['user_id'];
$deptId   = $_POST['dept_id'];
$appDate  = $_POST['app_date'];
$appTime  = $_POST['app_time'];
$visitFor = $_POST['visit_for'];

$stmt = $conn->prepare("INSERT INTO appointments (user_Id, app_time, app_date, visit_for, dept_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $userId, $appTime, $appDate, $visitFor, $deptId);

if ($stmt->execute()) {
    echo "<script>alert('Appointment booked successfully!'); window.history.back();</script>";
} else {
    echo "<script>alert('Error booking appointment.'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>
