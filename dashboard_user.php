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
        #trackAppointment,
        #bookAppointment {
            flex-direction: column;
        }


        .department-container,
        .appointment-form {
            max-width: 600px;
            /* margin: auto; */
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .appointment-form {
            border: none;
        }

        .appointment-form {
            display: none;
        }

        .hidden {
            display: none;
        }

        button {
            padding: 8px 15px;
            margin-top: 10px;
            cursor: pointer;
        }

        strong {
            font-size: 16px;
            /* color: #000; */
            font-weight: 500;
        }

        #AllApps-content,
        #approvedApps-content,
        #rejectedApps-content,
        #pendingApps-content {
            flex-direction: column;
        }
    </style>
    <script>
        function bookAppointment(deptId, deptName, hospitalName) {
            document.getElementById('department-list').style.display = 'none';
            document.getElementById('appointment-form').style.display = 'block';
            document.getElementById('dept_id_input').value = deptId;
            document.getElementById('dept_name_display').innerText = deptName;
            document.getElementById('hospital_name_display').innerText = hospitalName;
        }

        function goBack() {
            document.getElementById('department-list').style.display = 'block';
            document.getElementById('appointment-form').style.display = 'none';
        }
    </script>
</head>

<body>
    <nav>
        <div id="nav-right">
            <img src="src/logo.jpg" alt="PIMS" id="logoImg">
        </div>
        <div id="nav-left">
            <div id="buttons">
                <div class="navbutton" onclick="redirectHome();">Welcome, <?= htmlspecialchars($user['user_Name']) ?>
                </div>
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
                <h2>Book Appointment</h2>
                <?php
                // session_start();
                include 'script/db_connection.php';

                // Ensure user is logged in
                if (!isset($_SESSION['user_Id'])) {
                    echo "<script>alert('Please login first.'); window.location.href='user_login.php';</script>";
                    exit;
                }

                $userId = $_SESSION['user_Id'];

                // Fetch departments and join hospitals
                $sql = "SELECT d.*, h.hospital_Name, h.hospital_Time_open, h.hospital_Time_close
                        FROM departments d
                        JOIN hospitals h ON d.hospital_Id = h.hospital_Id
                        ORDER BY h.hospital_Name ASC";

                $result = $conn->query($sql);
                ?>

                <div id="department-list">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="department-container">
                                <p><strong>Hospital:</strong> <?= htmlspecialchars($row['hospital_Name']) ?></p>
                                <p><strong>Department:</strong> <?= htmlspecialchars($row['dept_Name']) ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($row['dept_Phone']) ?></p>
                                <p><strong>Description:</strong> <?= htmlspecialchars($row['Dept_Description']) ?></p>
                                <p><strong>Timing:</strong> <?= htmlspecialchars($row['hospital_Time_open']) ?> -
                                    <?= htmlspecialchars($row['hospital_Time_close']) ?>
                                </p>
                                <button
                                    onclick="bookAppointment('<?= $row['dept_Id'] ?>', '<?= htmlspecialchars($row['dept_Name']) ?>', '<?= htmlspecialchars($row['hospital_Name']) ?>')">Book
                                    Appointment</button>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="text-align:center;">No departments found.</p>
                    <?php endif; ?>
                </div>

                <!-- Appointment Form -->
                <div id="appointment-form" class="appointment-form">
                    <form action="script/submit_appointment.php" method="post">
                        <span id="hospital_name_display"></span>
                        <span id="dept_name_display"></span>

                        <input type="hidden" name="user_id" value="<?= $userId ?>">
                        <input type="hidden" name="dept_id" id="dept_id_input">

                        <label for="app_date">Date:</label>
                        <input type="date" name="app_date" id="app_date" required>

                        <label for="app_time">Time:</label>
                        <input type="time" name="app_time" id="app_time" required>

                        <label for="visit_for">Reason for Visit:</label>
                        <input type="text" name="visit_for" id="visit_for" required>

                        <div>
                            <button type="submit">Submit Appointment</button>
                            <button type="button" onclick="goBack()">Cancel</button>
                        </div>
                    </form>
                </div>

            </div>

            <div id="trackAppointment">
                <h2>My Appointments</h2>
                <div id="listApps">
                    <div id="AllApps" class="appTabNames" onclick="switchUserApps('all');">All</div>
                    <div id="pendingApps" class="appTabNames" onclick="switchUserApps('pending');">Pending</div>
                    <div id="approvedApps" class="appTabNames" onclick="switchUserApps('approved');">Approved</div>
                    <div id="rejectedApps" class="appTabNames" onclick="switchUserApps('rejected');">Rejected</div>
                </div>

                <?php
                // session_start();
                include 'script/db_connection.php';

                if (!isset($_SESSION['user_Id'])) {
                    die("Unauthorized.");
                }

                $userId = $_SESSION['user_Id'];

                // Fetch all appointments for this user
                $sql = "SELECT a.app_Id, a.app_date, a.app_time, a.visit_for, 
                               d.dept_Name, h.hospital_Name,
                               ap.approval_Status
                        FROM appointments a
                        JOIN departments d ON a.dept_id = d.dept_Id
                        JOIN hospitals h ON d.hospital_Id = h.hospital_Id
                        LEFT JOIN approval ap ON ap.app_Id = a.app_Id
                        WHERE a.user_Id = ?
                        ORDER BY a.app_date DESC, a.app_time DESC";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                // Containers for different status types
                $allApps = "";
                $approvedApps = "";
                $rejectedApps = "";
                $pendingApps = "";

                while ($row = $result->fetch_assoc()) {
                    $status = $row['approval_Status'] ?? 'Pending';

                    $card = '<div class="appointment-card" style="border:1px solid #ccc; padding:12px; margin:12px 0;">
                                <p><strong>Hospital:</strong> ' . htmlspecialchars($row['hospital_Name']) . '</p>
                                <p><strong>Department:</strong> ' . htmlspecialchars($row['dept_Name']) . '</p>
                                <p><strong>Date:</strong> ' . htmlspecialchars($row['app_date']) . '</p>
                                <p><strong>Time:</strong> ' . htmlspecialchars($row['app_time']) . '</p>
                                <p><strong>Visit For:</strong> ' . htmlspecialchars($row['visit_for']) . '</p>
                                <p><strong>Status:</strong> <span style="float:right; font-weight:bold;">' . htmlspecialchars($status) . '</span></p>
                            </div>';

                    // Append to All
                    $allApps .= $card;

                    // Append to status-specific
                    if ($status === 'Approved') {
                        $approvedApps .= $card;
                    } elseif ($status === 'Rejected') {
                        $rejectedApps .= $card;
                    } else {
                        $pendingApps .= $card;
                    }
                }

                $stmt->close();
                $conn->close();
                ?>

                <div id="appsContainer">
                    <div id="AllApps-content" style="display:flex;">

                    </div>
                    <div id="pendingApps-content" style="display:none;">

                    </div>
                    <div id="approvedApps-content" style="display:none;">

                    </div>
                    <div id="rejectedApps-content" style="display:none;">

                    </div>
                </div>
            </div>

            <!-- Inject into containers -->
            <script>
                document.getElementById("AllApps-content").innerHTML = `<?= $allApps ?>`;
                document.getElementById("pendingApps-content").innerHTML = `<?= $pendingApps ?>`;
                document.getElementById("approvedApps-content").innerHTML = `<?= $approvedApps ?>`;
                document.getElementById("rejectedApps-content").innerHTML = `<?= $rejectedApps ?>`;
            </script>


            <div id="reports">
                <h2>Your Reports</h2>
            </div>

            <div id="prescriptions">
                <h2>Your Prescriptions</h2>
            </div>

        </div>
    </section>

    <script src="script/dashboard.js"></script>
    <script>
        allUserHidden();
        document.getElementById('myDetails').style.display = "flex"
    </script>
</body>

</html>