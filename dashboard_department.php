<?php
session_start();
include 'script/db_connection.php';

if (!isset($_SESSION['dept_Id'])) {
    header("Location: manage.html");
    exit;
}

$dept_Id = $_SESSION['dept_Id'];

// Fetch department and its hospital in one query
$sql = "SELECT d.dept_Name, h.hospital_Name 
        FROM departments d
        JOIN hospitals h ON d.hospital_Id = h.hospital_Id
        WHERE d.dept_Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dept_Id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Portal 🏬 - PIMS</title>
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/form.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #requests,
        #attendence,
        #present {
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
                <div class="navbutton"><?= htmlspecialchars($data['dept_Name']) ?> |
                    <?= htmlspecialchars($data['hospital_Name']) ?>
                </div>
                <div class="navbutton" id="activePage" onclick="LogoutAdmin();">Logout</div>
            </div>
        </div>
    </nav>

    <section id="dashboardContainer">
        <div id="rightDashBar">
            <div id="buttonTray">
                <div class="tabButton active" onclick="switchTabDept('requests');">
                    <i class="fas fa-calendar-check"></i>Appointment Requests
                </div>
                <div class="tabButton" onclick="switchTabDept('attendence');">
                    <i class="fas fa-user"></i>Appointment Attendence
                </div>
                <div class="tabButton" onclick="switchTabDept('present');">
                    <i class="fa-solid fa-user-check"></i>Present
                </div>
            </div>
        </div>

        <div id="leftDataBar">

            <div id="requests">
                <h2>Appointment Requests</h2>
                <?php
                // session_start();
                include 'script/db_connection.php';

                // Check if department is logged in
                if (!isset($_SESSION['dept_Id'])) {
                    echo "window.location.href = 'manage.html';</script>";
                    exit;
                }

                $dept_Id = $_SESSION['dept_Id'];

                // Fetch only pending appointments (not in approval table)
                $sql = "SELECT a.app_Id, a.app_time, a.app_date, a.visit_for, u.user_Name, u.user_Gender
                        FROM appointments a
                        JOIN user_details u ON a.user_Id = u.user_Id
                        WHERE a.dept_id = ?
                          AND a.app_Id NOT IN (SELECT app_Id FROM approval)
                        ORDER BY a.app_date ASC, a.app_time ASC";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $dept_Id);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <script>
                    function hideAppCard(appId) {
                        const card = document.getElementById("appCard-" + appId);
                        if (card) {
                            alert("Patient Appointment Managed Successfully.✅");
                            card.style.display = "none";
                        }
                        return true; // Continue with form submission
                    }
                </script>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="appointment-card" style="border:1px solid #ccc; padding:12px; margin:12px 0;"
                            id="appCard-<?= $row['app_Id'] ?>">
                            <p><strong>User:</strong> <?= htmlspecialchars($row['user_Name']) ?></p>
                            <p><strong>Gender:</strong> <?= htmlspecialchars($row['user_Gender']) ?></p>
                            <p><strong>Date:</strong> <?= htmlspecialchars($row['app_date']) ?> | <strong>Time:</strong>
                                <?= htmlspecialchars($row['app_time']) ?></p>
                            <p><strong>Visit For:</strong> <?= htmlspecialchars($row['visit_for']) ?></p>

                            <form action="script/handle_approval.php" method="post" style="flex-direction:row;padding:0 10px;"
                                onsubmit="return hideAppCard(<?= $row['app_Id'] ?>)">
                                <input type="hidden" name="app_Id" value="<?= $row['app_Id'] ?>">
                                <button type="submit" name="status" value="Approved">Approve</button>
                                <button type="submit" name="status" value="Rejected"
                                    style="background-color:red; color:white;">Reject</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No pending appointment requests found for your department.</p>
                <?php endif; ?>

                <?php
                $stmt->close();
                $conn->close();
                ?>


            </div>

            <div id="attendence">
                <h2>Appointment Attendence</h2>

                <script>
                    function hideAppAttendCard(appId) {
                        const card = document.getElementById("appAttendCard-" + appId);
                        if (card) {
                            alert("Patient Attendence Managed Successfully.✅");
                            card.style.display = "none";
                        }
                        return true; // Continue with form submission
                    }
                </script>

                <?php
                include 'script/db_connection.php';
                // session_start();
                
                if (!isset($_SESSION['dept_Id'])) {
                    die("Unauthorized access.");
                }

                $deptId = $_SESSION['dept_Id'];

                // Fetch approved appointments for this department
                $sql = "SELECT a.app_Id, a.user_Id, a.app_date, a.app_time, a.visit_for, u.user_Name
                        FROM appointments a
                        JOIN approval ap ON a.app_Id = ap.app_Id
                        JOIN user_details u ON a.user_Id = u.user_Id
                        WHERE a.dept_id = ? AND ap.approval_Status = 'Approved'
                        AND NOT EXISTS (SELECT 1 FROM visit v WHERE v.app_Id = a.app_Id)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $deptId);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="appointment-card" id="appAttendCard-<?= $row['app_Id'] ?>"
                            style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                            <p><strong>User:</strong> <?= htmlspecialchars($row['user_Name']) ?></p>
                            <p><strong>Date:</strong> <?= htmlspecialchars($row['app_date']) ?> |
                                <strong>Time:</strong> <?= htmlspecialchars($row['app_time']) ?>
                            </p>
                            <p><strong>Visit For:</strong> <?= htmlspecialchars($row['visit_for']) ?></p>

                            <form action="script/handle_visit.php" method="post" style="flex-direction:row;padding:0 10px;"
                                onsubmit="return hideAppAttendCard(<?= $row['app_Id'] ?>);">
                                <input type="hidden" name="app_Id" value="<?= $row['app_Id'] ?>">
                                <button type="submit" name="status" value="Visited">Present</button>
                                <button type="submit" name="status" value="Cancelled" style="background:red; color:#fff;">Not
                                    Present</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No approved appointments found.</p>
                <?php endif; ?>

                <?php $stmt->close();
                $conn->close(); ?>
            </div>

            <div id="present">
                <h2>Present Available Clients</h2>

                <?php
                include 'script/db_connection.php';

                if (!isset($_SESSION['dept_Id'])) {
                    die("Unauthorized access.");
                }

                $dept_Id = $_SESSION['dept_Id'];

                // Fetch all 'Visited' clients for this department along with check if report exists
                $sql = "SELECT 
                v.app_Id, v.Visit_Time, a.app_date, a.app_time, a.visit_for,
                u.user_Name, u.user_Gender,
                r.app_Id AS report_exists
            FROM visit v
            JOIN appointments a ON v.app_Id = a.app_Id
            JOIN user_details u ON a.user_Id = u.user_Id
            LEFT JOIN report r ON v.app_Id = r.app_Id
            WHERE a.dept_id = ? AND v.Visit_Status = 'Visited'
            ORDER BY a.app_date DESC, v.Visit_Time DESC";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $dept_Id);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>

                <script>
                    function deleteVisitCard(appId) {
                        const card = document.getElementById("visitCard-" + appId);
                        fetch("script/delete_visit.php", {
                            method: "POST",
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: "app_Id=" + encodeURIComponent(appId)
                        })
                            .then(response => response.text())
                            .then(data => {
                                // alert(data);
                                if (card) card.style.display = "none";
                            });
                    }
                </script>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="visited-card" id="visitCard-<?= $row['app_Id'] ?>"
                            style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
                            <p><strong>Name:</strong> <?= htmlspecialchars($row['user_Name']) ?></p>
                            <p><strong>Gender:</strong> <?= htmlspecialchars($row['user_Gender']) ?></p>
                            <p><strong>Visit For:</strong> <?= htmlspecialchars($row['visit_for']) ?></p>
                            <p><strong>Date:</strong> <?= htmlspecialchars($row['app_date']) ?> |
                                <strong>Time:</strong> <?= htmlspecialchars($row['app_time']) ?>
                            </p>
                            <p><strong>Visited At:</strong> <?= htmlspecialchars($row['Visit_Time']) ?></p>

                            <?php if (empty($row['report_exists'])): ?>
                                <button onclick="deleteVisitCard(<?= $row['app_Id'] ?>)" style="background:red; color:white;">
                                    Delete / Absent
                                </button>
                            <?php else: ?>
                                <button disabled style="background:gray; color:white; cursor:not-allowed;">
                                    Report Submitted
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No visited clients found.</p>
                <?php endif; ?>

                <?php
                $stmt->close();
                $conn->close();
                ?>
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