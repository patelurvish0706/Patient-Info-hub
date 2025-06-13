<?php
session_start();
include 'script/db_connection.php';
if (!isset($_SESSION['dept_Id'])) {
    header("Location: manage.html");
    exit;
}

$dept_Id = $_SESSION['dept_Id'];
$dept_Name = $_SESSION['dept_Name'];

$sql = "SELECT * FROM departments WHERE dept_Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dept_Id);
$stmt->execute();
$result = $stmt->get_result();
$dept = $result->fetch_assoc();

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
                <div class="navbutton"><?= htmlspecialchars($dept['dept_Name']) ?></div>
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
                    echo "<script>alert('Unauthorized access. Please login first.'); window.location.href = 'index.php';</script>";
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
                            alert("Request submitted successfully.");
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

                            <form action="script/handle_approval.php" method="post" style="margin-top:10px;"
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