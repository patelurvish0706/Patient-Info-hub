<?php
session_start();
include 'db_connection.php';  // adjust if path is different

if (!isset($_SESSION['user_Id'])) {
    die("Unauthorized.");
}

$user_Id = $_SESSION['user_Id'];

$name     = $_POST['reg-user-name'];
$dob      = $_POST['reg-user-dob'];
$gender   = $_POST['reg-user-gender'];
$phone    = $_POST['reg-user-phone'];
$address  = $_POST['reg-user-address'];

$sql = "UPDATE user_details SET 
            user_Name = ?, 
            user_DOB = ?, 
            user_Gender = ?, 
            user_Phone = ?, 
            user_Address = ?
        WHERE user_Id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $name, $dob, $gender, $phone, $address, $user_Id);

if ($stmt->execute()) {
    echo "<script>window.history.back();</script>";
} else {
    echo "<script>alert('Update failed: " . $stmt->error . "'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>
