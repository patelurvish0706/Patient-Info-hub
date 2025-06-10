<?php
session_start();
include 'db_connection.php'; // adjust if path differs

if (!isset($_SESSION['admin_Id'])) {
    header("Location: manage.html");
    exit;
}

$adminId = $_SESSION['admin_Id'];

// Collect form data
$name     = trim($_POST['hospital-name']);
$email    = trim($_POST['hospital-email']);
$timeOpen = $_POST['hospital-time-open'];
$timeClose= $_POST['hospital-time-close'];
$phone    = trim($_POST['hospital-phone']);
$lat      = $_POST['hospital-latitude'];
$long     = $_POST['hospital-longitude'];

// Validate all fields are not empty
if (empty($name) || empty($email) || empty($timeOpen) || empty($timeClose) || empty($phone) || $lat === '' || $long === '') {
    die("All fields are required and cannot be null.");
}

// Check if admin already has a hospital record
$checkQuery = $conn->prepare("SELECT hospital_Id FROM hospitals WHERE admin_Id = ?");
$checkQuery->bind_param("i", $adminId);
$checkQuery->execute();
$result = $checkQuery->get_result();

if ($result->num_rows > 0) {
    // Hospital exists: perform update
    $update = $conn->prepare("UPDATE hospitals 
        SET hospital_Name = ?, hospital_Email = ?, hospital_Time_open = ?, hospital_Time_close = ?, hospital_Phone = ?, hospital_Lat = ?, hospital_Long = ? 
        WHERE admin_Id = ?");
    $update->bind_param("ssssddsi", $name, $email, $timeOpen, $timeClose, $phone, $lat, $long, $adminId);

    if ($update->execute()) {
        // echo "Hospital details updated successfully.";
        header("Location: ../dashboard_admin.php");
    } else {
        echo "Error updating hospital: " . $update->error;
    }

    $update->close();
} else {
    // Hospital does not exist: insert new
    $insert = $conn->prepare("INSERT INTO hospitals 
        (admin_Id, hospital_Name, hospital_Email, hospital_Time_open, hospital_Time_close, hospital_Phone, hospital_Lat, hospital_Long) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("isssssdd", $adminId, $name, $email, $timeOpen, $timeClose, $phone, $lat, $long);

    if ($insert->execute()) {
        echo "Hospital registered successfully.";
    } else {
        echo "Error inserting hospital: " . $insert->error;
    }

    $insert->close();
}

$conn->close();
?>
