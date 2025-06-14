<?php
session_start();
include 'script/db_connection.php';
if (!isset($_SESSION['doct_Id'])) {
    header("Location: manage.html");
    exit;
}

$doct_Id = $_SESSION['doct_Id'];
$doct_Name = $_SESSION['doct_Name'];

$sql = "SELECT * FROM doctors WHERE doct_Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doct_Id);
$stmt->execute();
$result = $stmt->get_result();
$doctors = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIMS - Health Board</title>
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/form.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #requests,
        #attendence {
            flex-direction: column;
        }
    </style>
</head>

<body>
    <nav>
        <div id="nav-right">
            <img src="src/logo.jpg" alt="PIMS" id="logoImg">
        </div>
        <div id="nav-left">
            <div id="buttons">
                <div class="navbutton">Welcome, Dr <?= htmlspecialchars($doctors['doct_Name']) ?></div>
                <div class="navbutton" id="activePage" onclick="LogoutAdmin();">Logout</div>
            </div>
        </div>
    </nav>

    <section id="dashboardContainer">
        <div id="rightDashBar">
            <div id="buttonTray">
                <div class="tabButton active" onclick="switchTabDept('requests');">
                    <i class="fas fa-calendar-check"></i>Available Patients
                </div>
                <div class="tabButton" onclick="switchTabDept('attendence');">
                    <i class="fas fa-user"></i>Appointment Attendence
                </div>
            </div>
        </div>

        <div id="leftDataBar">

            <div id="requests">
                <h2>Appointment Requests</h2>
            </div>

            <div id="attendence">
                <h2>Appointment Attendence</h2>
            </div>

        </div>
    </section>

    <script src="script/dashboard.js"></script>
    <script>
        allDepartmentHidden()
        document.getElementById('requests').style.display = "flex"
    </script>
</body>

</html>