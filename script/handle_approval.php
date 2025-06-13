<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['app_Id'], $_POST['status'])) {
    $app_Id = intval($_POST['app_Id']);
    $status = $_POST['status'];

    // Only allow Approved or Rejected
    if (!in_array($status, ['Approved', 'Rejected'])) {
        echo "<script>alert('Invalid status.'); history.back();</script>";
        exit;
    }

    // Check if approval record already exists
    $checkStmt = $conn->prepare("SELECT * FROM approval WHERE app_Id = ?");
    $checkStmt->bind_param("i", $app_Id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Update existing record
        $updateStmt = $conn->prepare("UPDATE approval SET approval_Status = ? WHERE app_Id = ?");
        $updateStmt->bind_param("si", $status, $app_Id);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // Insert new approval
        $insertStmt = $conn->prepare("INSERT INTO approval (app_Id, approval_Status) VALUES (?, ?)");
        $insertStmt->bind_param("is", $app_Id, $status);
        $insertStmt->execute();
        $insertStmt->close();
    }

    echo "<script>alert('Appointment $status successfully.'); window.history.back();</script>";
} else {
    echo "<script>alert('Invalid request.'); history.back();</script>";
}

$conn->close();
?>
