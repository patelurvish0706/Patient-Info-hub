<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $app_Id = intval($_POST['app_Id']);
    $checkup_outcome = trim($_POST['checkup_outcome']);
    $prescriptions = trim($_POST['prescriptions']);
    $suggestion = trim($_POST['suggestion']);

    // Check if a report already exists
    $checkSql = "SELECT app_Id FROM report WHERE app_Id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $app_Id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Report already exists for this appointment.'); history.back();</script>";
    } else {
        $sql = "INSERT INTO report (app_Id, checkup_outcome, prescriptions, suggestion)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $app_Id, $checkup_outcome, $prescriptions, $suggestion);

        if ($stmt->execute()) {
            echo "<script>alert('Report submitted successfully.'); window.location.href='../dashboard_doctor.php';</script>";
        } else {
            echo "<script>alert('Failed to submit report.'); history.back();</script>";
        }

        $stmt->close();
    }
    $checkStmt->close();
}

$conn->close();
?>
