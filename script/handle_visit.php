<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['app_Id'], $_POST['status'])) {
    $app_Id = intval($_POST['app_Id']);
    $status = $_POST['status'];

    // Get current time in 12-hour format with AM/PM
    date_default_timezone_set('Asia/Kolkata'); // Set your timezone if needed
    $current_time = date("h:i A"); // e.g., 02:45 PM

    $stmt = $conn->prepare("INSERT INTO visit (app_Id, Visit_Status, Visit_Time) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $app_Id, $status, $current_time);

    if ($stmt->execute()) {
        echo "<script>window.history.back();</script>";
    } else {
        echo "<script>alert('Error saving visit.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>
