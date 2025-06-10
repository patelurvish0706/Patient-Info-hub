<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['login-admin-email']);
    $password = trim($_POST['login-admin-password']);

    if (empty($email) || empty($password)) {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("SELECT admin_Id, admin_Password FROM Admin WHERE admin_Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['admin_Password'])) {
            // ✅ Password match
            $_SESSION['admin_Id'] = $row['admin_Id'];
            $_SESSION['admin_Email'] = $email;

            header("Location: ../dashboard_admin.php"); // 👈 redirect to admin dashboard
            exit;
        } else {
            // echo "Incorrect password.";
            echo "<script>
                window.history.back();
                alert('Invalid Password');
            </script>";
        }
    } else {
        echo "Admin not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
