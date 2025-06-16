<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app_Id = $_POST['app_Id'];
    $checkup_outcome = $_POST['checkup_outcome'];
    $prescriptions = $_POST['prescriptions'];
    $suggestion = $_POST['suggestion'];

    $stmt = $conn->prepare("UPDATE report 
                            SET checkup_outcome = ?, prescriptions = ?, suggestion = ?
                            WHERE app_Id = ?");
    $stmt->bind_param("sssi", $checkup_outcome, $prescriptions, $suggestion, $app_Id);
    
    if ($stmt->execute()) {
        header("Location: ../dashboard_doctor.php?status=success");
    } else {
        echo "Error updating report.";
    }

    $stmt->close();
    $conn->close();
}
?>
