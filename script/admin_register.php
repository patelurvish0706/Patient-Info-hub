<?php
// admin_register.php
include 'db_connection.php'; // assume this file contains your DB connection logic

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['register-admin-email']);
    $password = trim($_POST['register-admin-password']);
    $repassword = trim($_POST['register-admin-repassword']);

    // Basic server-side validation
    if (empty($email) || empty($password) || empty($repassword)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM Admin WHERE admin_Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        //  die("Admin with this email already exists.");
        echo "<script>
        alert('Email already Existed Try another.');
        </script>";
    }

    if ($password !== $repassword) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new admin
    $stmt = $conn->prepare("INSERT INTO Admin (admin_Email, admin_Password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        // echo "Admin registered successfully!";
        echo "<script>
        alert('Admin registered successfully!');
        </script>";
        header("Location: ../manage.html");

    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
