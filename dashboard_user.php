<?php
session_start();
include 'script/db_connection.php';
if (!isset($_SESSION['user_Id'])) {
    header("Location: login.html");
    exit;
}

$user_Id = $_SESSION['user_Id'];
$userName = isset($_SESSION['user_Name']) ? $_SESSION['user_Name'] : "User";

$sql = "SELECT * FROM user_details WHERE user_Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_Id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
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
        #trackAppointment {
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
                <div class="navbutton" onclick="redirectHome();">Welcome, <?= htmlspecialchars($user['user_Name']) ?></div>
                <div class="navbutton" id="activePage" onclick="LogoutUser();">Logout</div>
            </div>
        </div>
    </nav>

    <section id="dashboardContainer">
        <div id="rightDashBar">
            <div id="buttonTray">
                <div class="tabButton active" onclick="switchTabUser('myDetails');">
                    <i class="fas fa-user"></i>My Details
                </div>
                <div class="tabButton" onclick="switchTabUser('BookAppoint');">
                    <i class="fas fa-calendar-check"></i>Book Appointment
                </div>
                <div class="tabButton" onclick="switchTabUser('trackAppoint');">
                    <i class="fas fa-search-location"></i>Track Appointment
                </div>
                <div class="tabButton" onclick="switchTabUser('Reports');">
                    <i class="fas fa-file-medical-alt"></i>Reports
                </div>
                <div class="tabButton" onclick="switchTabUser('Prescrip');">
                    <i class="fas fa-prescription-bottle-alt"></i>Prescriptions
                </div>
            </div>
        </div>

        <div id="leftDataBar">

            <div id="myDetails">
                <form action="script/user_update.php" method="post">
                    <h2>My Details</h2>
                    <p>You can change or modify your details as required.</p>

                    <label for="reg-user-name">Name:</label>
                    <input type="text" name="reg-user-name" id="reg-user-name"
                        value="<?= htmlspecialchars($user['user_Name']) ?>" required>

                    <label for="reg-user-dob">Date Of Birth:</label>
                    <input type="date" name="reg-user-dob" id="reg-user-dob" value="<?= $user['user_DOB'] ?>" required>

                    <label for="reg-user-gender">Gender:</label>
                    <select name="reg-user-gender" id="reg-user-gender" required>
                        <option value="" disabled>Select your gender</option>
                        <option value="male" <?= $user['user_Gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $user['user_Gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $user['user_Gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                        <option value="prefer_not_to_say" <?= $user['user_Gender'] === 'prefer_not_to_say' ? 'selected' : '' ?>>Prefer not to say</option>
                    </select>

                    <label for="reg-user-phone">Phone:</label>
                    <input type="number" name="reg-user-phone" id="reg-user-phone"
                        value="<?= htmlspecialchars($user['user_Phone']) ?>" required>

                    <label for="reg-user-address">Address:</label>
                    <input type="text" name="reg-user-address" id="reg-user-address"
                        value="<?= htmlspecialchars($user['user_Address']) ?>" required>

                    <button type="submit">Save Details</button>
                </form>
            </div>

            <div id="bookAppointment">
            </div>

            <div id="trackAppointment">
                <h2>My Appointments</h2>
                <div id="listApps">
                    <div id="pendingApps" class="appTabNames">Pending</div>
                    <div id="approvedApps" class="appTabNames">Approved</div>
                    <div id="previousApps" class="appTabNames">Previous</div>
                </div>

                <div id="appsContainer">
                    <div id="appBox">
                        <div id="appInfo">
                            <div id="dept-name">CardioLogy Section - The Great Civil Hospital</div>
                            <div id="dept-phone">Enquiry: 9998654979</div>
                            <div id="doct-name">Dr. Sarah Smith</div>
                            <div id="date-time">
                                <div id="app-date">12-9</div>
                                <div id="app-time">6:30 PM</div>
                            </div>
                        </div>
                        <div id="appStatus">Pending</div>
                    </div>

                    <div id="appBox">
                        <div id="appInfo">
                            <div id="dept-name">CardioLogy Section - The Great Civil Hospital</div>
                            <div id="dept-phone">Enquiry: 9998654979</div>
                            <div id="doct-name">Dr. Sarah Smith</div>
                            <div id="date-time">
                                <div id="app-date">12-9</div>
                                <div id="app-time">6:30 PM</div>
                            </div>
                        </div>
                        <div id="appStatus">Pending</div>
                    </div>
                </div>
            </div>

            <div id="reports"></div>

            <div id="prescriptions"></div>

        </div>
    </section>

    <script src="script/dashboard.js"></script>
    <script>
        allUserHidden();
        document.getElementById('myDetails').style.display = "flex"
    </script>
</body>

</html>