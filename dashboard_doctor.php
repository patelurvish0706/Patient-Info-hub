<?php
session_start();
include 'script/db_connection.php';
if (!isset($_SESSION['doct_Id'])) {
    header("Location: manage.html");
    exit;
}

$doct_Id = $_SESSION['doct_Id'];
$doct_Name = $_SESSION['doct_Name'];

$sql = "
    SELECT doctors.*, departments.dept_Name 
    FROM doctors 
    JOIN departments ON doctors.dept_Id = departments.dept_Id 
    WHERE doctors.doct_Id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doct_Id);
$stmt->execute();
$result = $stmt->get_result();
$doctors = $result->fetch_assoc();

$_SESSION['dept_Id'] = $doctors['dept_Id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Portal 👩🏻‍⚕️ - PIMS</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚕️</text></svg>">
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/form.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #listPatients,
        #submitedReps {
            flex-direction: column;
        }

        textarea {
            color: #000a;
            height: 50px;
            padding: 10px;
            font-size: 18px;
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
                <div class="navbutton">
                    Welcome, Dr <?= htmlspecialchars($doctors['doct_Name']) ?>
                    [<?= htmlspecialchars($doctors['dept_Name']) ?>]</div>
                <div class="navbutton" id="activePage" onclick="LogoutAdmin();">Logout</div>
            </div>
        </div>
    </nav>

    <section id="dashboardContainer">
        <div id="rightDashBar">
            <div id="buttonTray">
                <div class="tabButton active" onclick="switchTabDoc('listPatients');">
                    <i class="fas fa-calendar-check"></i>Available Patients
                </div>
                <div class="tabButton" onclick="switchTabDoc('submitedReps');">
                    <i class="fas fa-user"></i>Today's Reports
                </div>
            </div>
        </div>

        <div id="leftDataBar">

            <div id="listPatients">
                <h2>Appointment Requests</h2>
                <?php
                include 'script/db_connection.php';

                if (!isset($_SESSION['doct_Id'])) {
                    header("Location: ../manage.html");
                    exit;
                }

                $doct_Id = $_SESSION['doct_Id'];

                // Get dept_Id from doctor table
                $stmt = $conn->prepare("SELECT dept_Id FROM doctors WHERE doct_Id = ?");
                $stmt->bind_param("i", $doct_Id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $dept_Id = $row['dept_Id'];

                // Get visited appointments for this department
                $sql = "SELECT a.app_Id, a.user_Id, a.visit_for, a.app_date, a.app_time, u.user_Name, u.user_DOB
                        FROM appointments a
                        JOIN visit v ON a.app_Id = v.app_Id
                        JOIN user_details u ON a.user_Id = u.user_Id
                        WHERE a.dept_id = ? AND v.Visit_Status = 'Visited'
                        AND NOT EXISTS (SELECT 1 FROM report r WHERE r.app_Id = a.app_Id)";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $dept_Id);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>

                <div id="reportFormSection" style="display:none; margin-bottom:10px;">
                    <form action="script/submit_report.php" method="post" id="reportForm">
                        <input type="hidden" name="app_Id" id="form_app_Id">
                        <p><strong>User:</strong> <span id="form_user_Name"></span></p>
                        <p><strong>DOB:</strong> <span id="form_user_DOB"></span></p>
                        <p><strong>Visit For:</strong> <span id="form_visit_For"></span></p>

                        <label>Checkup Outcome:</label>
                        <textarea name="checkup_outcome" required></textarea>

                        <label>Prescriptions:</label>
                        <textarea name="prescriptions" required></textarea>

                        <label>Suggestion:</label>
                        <textarea name="suggestion" required></textarea>

                        <div>
                            <button type="submit" onclick="validateRepSub(event)">Submit Report</button>
                            <button type="button" onclick="backToList()">Back</button>
                        </div>
                    </form>
                </div>

                <div id="patientList">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="patient-card" id="patient-<?= $row['app_Id'] ?>"
                                style="border:1px solid #aaa; padding:12px; margin-bottom:10px;">
                                <p><strong>User:</strong> <?= htmlspecialchars($row['user_Name']) ?></p>
                                <p><strong>Date of Birth:</strong> <?= htmlspecialchars($row['user_DOB']) ?></p>
                                <p><strong>Date:</strong> <?= htmlspecialchars($row['app_date']) ?> |
                                    <strong>Time:</strong> <?= htmlspecialchars($row['app_time']) ?>
                                </p>
                                <p><strong>Visit For:</strong> <?= htmlspecialchars($row['visit_for']) ?></p>
                                <button
                                    onclick="showReportForm('<?= $row['app_Id'] ?>', '<?= htmlspecialchars(addslashes($row['user_Name'])) ?>', '<?= $row['user_DOB'] ?>', '<?= htmlspecialchars(addslashes($row['visit_for'])) ?>')">Make
                                    Report</button>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No visited appointments available.</p>
                    <?php endif; ?>
                </div>

                <script>
                    function showReportForm(app_Id, user_Name, user_DOB, visit_For) {
                        document.getElementById('reportFormSection').style.display = 'block';
                        document.getElementById('patientList').style.display = 'none';
                        document.getElementById('form_app_Id').value = app_Id;
                        document.getElementById('form_user_Name').innerText = user_Name;
                        document.getElementById('form_user_DOB').innerText = user_DOB;
                        document.getElementById('form_visit_For').innerText = visit_For;
                    }

                    function backToList() {
                        document.getElementById('reportFormSection').style.display = 'none';
                        document.getElementById('patientList').style.display = 'block';
                    }
                </script>

            </div>

            <div id="submitedReps">
                <h2>Today's Reports</h2>

                <?php
                // session_start();
                include 'script/db_connection.php';

                if (!isset($_SESSION['doct_Id'])) {
                    header("Location: ../manage.html");
                    exit;
                }

                $doct_Id = $_SESSION['doct_Id'];

                // Get dept_Id of doctor
                $stmt = $conn->prepare("SELECT dept_Id FROM doctors WHERE doct_Id = ?");
                $stmt->bind_param("i", $doct_Id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $dept_Id = $row['dept_Id'];

                // Fetch reports for patients in this department
                $sql = "SELECT r.app_Id, r.checkup_outcome, r.prescriptions, r.suggestion,
               u.user_Name, a.visit_for, a.app_date
        FROM report r
        JOIN appointments a ON r.app_Id = a.app_Id
        JOIN user_details u ON a.user_Id = u.user_Id
        WHERE a.dept_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $dept_Id);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <form action="script/update_report_process.php" id="update-rep-doc" method="post"
                            style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">
                            <input type="hidden" name="app_Id" value="<?= $row['app_Id'] ?>">

                            <p><strong>User:</strong> <?= htmlspecialchars($row['user_Name']) ?></p>
                            <p><strong>Visit For:</strong> <?= htmlspecialchars($row['visit_for']) ?></p>
                            <p><strong>Date:</strong> <?= htmlspecialchars($row['app_date']) ?></p>
                            
                            <label>Checkup Outcome:</label>
                            <textarea name="checkup_outcome"
                                required><?= htmlspecialchars($row['checkup_outcome']) ?></textarea>

                            <label>Prescriptions:</label>
                            <textarea name="prescriptions" required><?= htmlspecialchars($row['prescriptions']) ?></textarea>

                            <label>Suggestion:</label>
                            <textarea name="suggestion" required><?= htmlspecialchars($row['suggestion']) ?></textarea>

                            <button type="submit" onclick="validateUpdateRepDoc(event)">Update Report</button>
                        </form>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No reports found for your department.</p>
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
        allDocTabHidden();
        document.getElementById('listPatients').style.display = "flex"
    </script>
</body>

</html>