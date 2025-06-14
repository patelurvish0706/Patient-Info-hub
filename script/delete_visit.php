<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['app_Id'])) {
    $app_Id = intval($_POST['app_Id']);

    $sql = "DELETE FROM visit WHERE app_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_Id);

    if ($stmt->execute()) {
    } else {
        echo "Failed to delete visit entry.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
