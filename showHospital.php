<?php
session_start();
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

<!DOCTYPE html>
<html>
<head>
    <title>Departments List</title>
    <style>
        .department-container, .appointment-form {
            max-width: 600px;
            margin: auto;
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
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

<h2 style="text-align:center;">Available Departments</h2>

<div id="department-list">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="department-container">
                <p><strong>Hospital:</strong> <?= htmlspecialchars($row['hospital_Name']) ?></p>
                <p><strong>Department:</strong> <?= htmlspecialchars($row['dept_Name']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($row['dept_Phone']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($row['Dept_Description']) ?></p>
                <p><strong>Timing:</strong> <?= htmlspecialchars($row['hospital_Time_open']) ?> - <?= htmlspecialchars($row['hospital_Time_close']) ?></p>
                <button onclick="bookAppointment('<?= $row['dept_Id'] ?>', '<?= htmlspecialchars($row['dept_Name']) ?>', '<?= htmlspecialchars($row['hospital_Name']) ?>')">Book Appointment</button>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">No departments found.</p>
    <?php endif; ?>
</div>

<!-- Appointment Form -->
<div id="appointment-form" class="appointment-form">
    <h3>Book Appointment</h3>
    <p><strong>Hospital:</strong> <span id="hospital_name_display"></span></p>
    <p><strong>Department:</strong> <span id="dept_name_display"></span></p>

    <form action="script/submit_appointment.php" method="post">
        <input type="hidden" name="user_id" value="<?= $userId ?>">
        <input type="hidden" name="dept_id" id="dept_id_input">

        <label for="app_date">Date:</label><br>
        <input type="date" name="app_date" id="app_date" required><br><br>

        <label for="app_time">Time:</label><br>
        <input type="time" name="app_time" id="app_time" required><br><br>

        <label for="visit_for">Reason for Visit:</label><br>
        <input type="text" name="visit_for" id="visit_for" required><br><br>

        <button type="submit">Submit Appointment</button>
        <button type="button" onclick="goBack()">Cancel</button>
    </form>
</div>

<?php $conn->close(); ?>
</body>
</html>
