<?php
session_start();
include 'db_connection.php'; // Adjust path if needed

// Get form data
$email = trim($_POST['login-user-email']);
$password = $_POST['login-user-password'];

// Check if user exists
$stmt = $conn->prepare("SELECT user_Id, user_Name, user_Password FROM user_details WHERE user_Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['user_Password'])) {
        // Set session
        $_SESSION['user_Id'] = $user['user_Id'];
        $_SESSION['user_Name'] = $user['user_Name'];

        echo "<script>window.location.href = '../dashboard_user.php';</script>";
    } else {
        echo "<script>alert('Incorrect password.'); history.back();</script>";
    }
} else {
    echo "<script>alert('No user found with this email.'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>
