<?php
include 'db_connection.php'; // Adjust path if needed

// Get form data
$name       = trim($_POST['reg-user-name']);
$dob        = $_POST['reg-user-dob'];
$gender     = $_POST['reg-user-gender'];
$phone      = trim($_POST['reg-user-phone']);
$address    = trim($_POST['reg-user-address']);
$email      = trim($_POST['reg-user-email']);
$password   = $_POST['reg-user-password'];
$repassword = $_POST['reg-user-repassword'];

// Validate passwords match
if ($password !== $repassword) {
    echo "<script>alert('Passwords do not match.'); history.back();</script>";
    exit();
}

// Check if email already exists
$check = $conn->prepare("SELECT * FROM user_details WHERE user_Email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('This email is already registered. Please use another.'); history.back();</script>";
    exit();
}

// Hash the password
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$hashedPassword = $password ;

// Insert into DB
$stmt = $conn->prepare("INSERT INTO user_details (user_Name, user_Email, user_Password, user_DOB, user_Phone, user_Gender, user_Address)
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $email, $hashedPassword, $dob, $phone, $gender, $address);

if ($stmt->execute()) {
    echo "<script>window.location.href = '../login.html';</script>";
} else {
    echo "<script>alert('Something went wrong during registration.'); history.back();</script>";
}
?>
