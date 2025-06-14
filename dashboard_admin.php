<?php
session_start();
include 'script/db_connection.php';
if (!isset($_SESSION['admin_Id'])) {
    header("Location: manage.html");
    exit;
}

$adminId = $_SESSION['admin_Id'];

// Check if hospital_Id is already stored in session
if (!isset($_SESSION['hospital_Id'])) {
    $stmt = $conn->prepare("SELECT hospital_Id FROM hospitals WHERE admin_Id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hospital = $result->fetch_assoc();
        $_SESSION['hospital_Id'] = $hospital['hospital_Id']; // ✅ Set session
    } else {
        $_SESSION['hospital_Id'] = null; // Optional: indicate no hospital yet
    }

    $stmt->close();
}

$conn->close();
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
    <script src="script/validate.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #manageHospital,
        #manageDepartment,
        #manageDoctor {
            flex-direction: column;
        }

        #manageDepartment,
        #manageDoctor {
            display: none;
        }

        #map {
            width: 100%;
            height: 300px;
        }

        #listDepts-content,
        #listDoctor-content {
            flex-direction: column;
        }

        #addDepts,
        #addDoctor {
            background-color: #1E90FF;
            color: #fff;
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
                <div class="navbutton" onclick="redirectHome();">Welcome, Admin</div>
                <div class="navbutton" id="activePage" onclick="LogoutAdmin();">Logout</div>
            </div>
        </div>
    </nav>

    <section id="dashboardContainer">
        <div id="rightDashBar">
            <div id="buttonTray">
                <div class="tabButton active" onclick="switchTabAdmin('mngHsptl');">
                    <i class="fa-solid fa-hospital"></i>Manage Hospital
                </div>
                <div class="tabButton" onclick="switchTabAdmin('mngDprtmnt');">
                    <i class="fa-solid fa-sitemap"></i>Manage Department
                </div>
                <div class="tabButton" onclick="switchTabAdmin('mngDctrs');">
                    <i class="fa-solid fa-user-doctor"></i>Manage Doctors
                </div>
            </div>
        </div>

        <div id="leftDataBar">

            <div id="manageHospital">
                <h2>Manage Hospital</h2>
                <div id="listApps">
                    <div id="newHospt" class="appTabNames">Hospital</div>
                </div>
                <div id="appsContainer">
                    <div id="newHospt-content">
                        <?php
                        // session_start();
                        include 'script/db_connection.php'; // adjust path if needed
                        
                        if (!isset($_SESSION['admin_Id'])) {
                            die("Unauthorized. Please login.");
                        }

                        $adminId = $_SESSION['admin_Id'];

                        // Initialize form fields
                        $hospitalName = "";
                        $hospitalEmail = "";
                        $timeOpen = "";
                        $timeClose = "";
                        $phone = "";
                        $lat = "";
                        $long = "";

                        // Check if data already exists
                        $stmt = $conn->prepare("SELECT * FROM hospitals WHERE admin_Id = ?");
                        $stmt->bind_param("i", $adminId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $hospital = $result->fetch_assoc();

                            $hospitalName = $hospital['hospital_Name'];
                            $hospitalEmail = $hospital['hospital_Email'];
                            $timeOpen = $hospital['hospital_Time_open'];
                            $timeClose = $hospital['hospital_Time_close'];
                            $phone = $hospital['hospital_Phone'];
                            $lat = $hospital['hospital_Lat'];
                            $long = $hospital['hospital_Long'];
                        }

                        $conn->close();
                        ?>
                        <form action="script/submit_hospital.php" method="post" id="Hospital-info-submit">
                            <p><b>Must Fill Hospital Detail Before Creating Departments.</b></p>

                            <label for="hospital-name">Hospital Name:</label>
                            <input type="text" name="hospital-name" id="hospital-name" placeholder="" value="<?= htmlspecialchars($hospitalName) ?>" required>

                            <label for="hospital-email">Hospital Email:</label>
                            <input type="email" name="hospital-email" id="hospital-email" placeholder="" value="<?= htmlspecialchars($hospitalEmail) ?>" required>

                            <label style="color:#f00a;">Set Open and Close time as 12:00 AM to 12:00 PM for 24x7.</label>

                            <label>Open at:</label>
                            <input type="time" name="hospital-time-open" id="hospital-time-open" value="<?= $timeOpen ?>" required>
                            <label>Close at:</label>
                            <input type="time" name="hospital-time-close" id="hospital-time-close" value="<?= $timeClose ?>" required>

                            <label for="hospital-phone">Phone:</label>
                            <input type="number" name="hospital-phone" id="hospital-phone" placeholder="" value="<?= htmlspecialchars($phone) ?>" required>

                            <label>Select Location:</label>
                            <div id="map" name="hospital-loc"></div>

                            <label for="hospital-latitude">Hospital Latitude</label>
                            <input type="number" name="hospital-latitude" id="hospital-latitude" step="any" value="<?= $lat ?>" required>

                            <label for="hospital-longitude">Hospital Longitude</label>
                            <input type="number" name="hospital-longitude" id="hospital-longitude" step="any" value="<?= $long ?>" required>

                            <button type="submit" onclick="validateHospitalSub(event);">Save Details</button>
                        </form>
                        <script>
                            let map, marker;
                            function initMap() {
                                const latInput = document.getElementById('hospital-latitude');
                                const lonInput = document.getElementById('hospital-longitude');

                                let lat = parseFloat(latInput.value) || 20.5937;
                                let lon = parseFloat(lonInput.value) || 78.9629;
                                let zoom = (latInput.value && lonInput.value) ? 16 : 5;

                                map = L.map('map').setView([lat, lon], zoom);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '© OpenStreetMap contributors'
                                }).addTo(map);

                                if (latInput.value && lonInput.value) {
                                    marker = L.marker([lat, lon]).addTo(map);
                                }

                                map.on('click', function (e) {
                                    latInput.value = e.latlng.lat.toFixed(7);
                                    lonInput.value = e.latlng.lng.toFixed(7);
                                    if (marker) {
                                        marker.setLatLng(e.latlng);
                                    } else {
                                        marker = L.marker(e.latlng).addTo(map);
                                    }
                                });

                                if (!latInput.value) {
                                    if (navigator.geolocation) {
                                        navigator.geolocation.getCurrentPosition((pos) => {
                                            const coords = [pos.coords.latitude, pos.coords.longitude];
                                            map.setView(coords, 16);
                                            marker = L.marker(coords).addTo(map);
                                            latInput.value = coords[0].toFixed(7);
                                            lonInput.value = coords[1].toFixed(7);
                                        });
                                    }
                                }
                            }
                            window.onload = initMap;
                        </script>
                    </div>
                </div>
            </div>

            <div id="manageDepartment">
                <h2>Manage Department</h2>
                <div id="listApps">
                    <div id="addDepts" class="appTabNames" onclick="showAddDepartment();">Add Departments</div>
                    <div id="listDepts" class="appTabNames" onclick="showListDepartment();">List Departments</div>
                </div>

                <div id="appsContainer">
                    <div id="addDepts-content" style="display:flex;">

                        <form action="script/submit_department.php" method="post" id="department-info-submit">
                            <p><b>Creating New Department.</b></p>

                            <label for="department-name">Department Name:</label>
                            <input type="text" name="department_name" id="department-name" placeholder="" required>

                            <label for="department-email">Department Email:</label>
                            <input type="email" name="department_email" id="department-email" placeholder="" required>

                            <label for="department-phone">Phone:</label>
                            <input type="number" name="department_phone" id="department-phone" placeholder=""  required>

                            <label for="department-password">Set Password:</label>
                            <input type="password" name="department_password" id="department-password" placeholder="" required>

                            <label for="department-description">Description: (What Services This Department Provide.)</label>
                            <input type="text" name="department_description" id="department-description" placeholder="" required>

                            <button type="submit" onclick="validateDepartmentSub(event)">Save Details</button>
                        </form>

                    </div>
                    <div id="listDepts-content" style="display: none;">
                        <p><b>List of Existing Departments</b></p>
                        <?php
                        include 'script/db_connection.php';     // adjust path as needed
                        
                        $hospitalId = $_SESSION['hospital_Id'];

                        /* ────────── FETCH ALL DEPARTMENTS FOR THIS HOSPITAL ────────── */
                        $stmt = $conn->prepare(
                            "SELECT dept_Id, dept_Name, dept_Email, dept_Phone, '' AS dept_Password,
                                    dept_Description
                                     FROM departments
                                     WHERE hospital_Id = ?
                                     ORDER BY dept_Name"
                        );
                        $stmt->bind_param("i", $hospitalId);
                        $stmt->execute();
                        $departments = $stmt->get_result();
                        ?>

                        <?php while ($row = $departments->fetch_assoc()): ?>
                            <form action="script/update_department.php" method="post">
                                <input type="hidden" name="dept_id" value="<?= $row['dept_Id']; ?>">

                                <label>Department Name:</label>
                                <input type="text" name="dept_name" value="<?= htmlspecialchars($row['dept_Name']); ?>"
                                    required>

                                <label>Department Email:</label>
                                <input type="email" name="dept_email" value="<?= htmlspecialchars($row['dept_Email']); ?>"
                                    required>

                                <label>Phone:</label>
                                <input type="text" name="dept_phone" value="<?= htmlspecialchars($row['dept_Phone']); ?>"
                                    required>

                                <label>New Password (leave blank to keep existing):</label>
                                <input type="password" name="dept_password" placeholder="••••••••">

                                <label>Description:</label>
                                <input type="text" name="dept_description"
                                    value="<?= htmlspecialchars($row['dept_Description']); ?>" required>

                                <button type="submit" onclick="departmentUpdated()">Update Department</button>
                            </form>
                        <?php endwhile;
                        $stmt->close();
                        $conn->close(); ?>
                    </div>
                </div>
            </div>

            <div id="manageDoctor">
                <h2>Manage Doctors</h2>
                <div id="listApps">
                    <div id="addDoctor" class="appTabNames" onclick="showAddDoctor();">Add Doctor</div>
                    <div id="listDoctor" class="appTabNames" onclick="showListDoctors();">List Doctors</div>
                </div>

                <div id="appsContainer">
                    <div id="addDoctor-content" style="display:flex;">
                        <?php
                        // session_start();
                        include 'script/db_connection.php'; // Update this path as needed
                        
                        if (!isset($_SESSION['admin_Id']) || !isset($_SESSION['hospital_Id'])) {
                            die("Unauthorized access.");
                        }

                        $adminId = $_SESSION['admin_Id'];

                        /* Fetch departments for current admin */
                        $stmt = $conn->prepare("SELECT dept_Id, dept_Name FROM departments WHERE admin_Id = ?");
                        $stmt->bind_param("i", $adminId);
                        $stmt->execute();
                        $departments = $stmt->get_result();
                        ?>

                        <form action="script/submit_doctor.php" method="post" id="doctor-info-submit">
                            <p><b>Creating New Doctor Profile.</b></p>

                            <label for="doctor-name">Doctor Name:</label>
                            <input type="text" name="doctor_name" id="doctor-name" placeholder="" required>

                            <label for="doctor-email">Doctor Email:</label>
                            <input type="email" name="doctor_email" id="doctor-email" placeholder="" required>

                            <label for="doctor-phone">Phone:</label>
                            <input type="number" name="doctor_phone" id="doctor-phone" placeholder="" required>

                            <label for="doctor-password">Set Password:</label>
                            <input type="password" name="doctor_password" id="doctor-password" placeholder="" required>

                            <label for="doctor-department">Department:</label>
                            <select name="doctor_department" id="doctor-department" required>
                                <option value="" disabled selected>Select your Department</option>
                                <?php while ($row = $departments->fetch_assoc()): ?>
                                    <option value="<?= $row['dept_Id'] ?>"><?= htmlspecialchars($row['dept_Name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>

                            <label for="doctor-description">Specialist:</label>
                            <input type="text" name="doctor_description" id="doctor-description" placeholder="" required>

                            <button type="submit" onclick="validateDoctorSub(event)">Save Details</button>
                        </form>
                    </div>

                    <div id="listDoctor-content" style="display:none;">
                        <p><b>List of Existing Doctors.</b></p>
                        <?php
                        // session_start();
                        include 'script/db_connection.php'; // Adjust path
                        
                        // if (!isset($_SESSION['admin_Id']) || !isset($_SESSION['hospital_Id'])) {
                        //     die("Unauthorized access.");
                        // }
                        
                        $adminId = $_SESSION['admin_Id'];
                        $hospitalId = $_SESSION['hospital_Id'];

                        // Get departments
                        $deptStmt = $conn->prepare("SELECT dept_Id, dept_Name FROM departments WHERE admin_Id = ?");
                        $deptStmt->bind_param("i", $adminId);
                        $deptStmt->execute();
                        $deptResult = $deptStmt->get_result();
                        $departments = [];
                        while ($row = $deptResult->fetch_assoc()) {
                            $departments[$row['dept_Id']] = $row['dept_Name'];
                        }

                        // Get doctors
                        $docStmt = $conn->prepare("SELECT * FROM doctors WHERE admin_Id = ? AND hospital_Id = ?");
                        $docStmt->bind_param("ii", $adminId, $hospitalId);
                        $docStmt->execute();
                        $doctors = $docStmt->get_result();
                        ?>

                        <?php while ($doctor = $doctors->fetch_assoc()): ?>
                            <form action="script/update_doctor.php" method="post" style="margin-bottom: 25px;">
                                <input type="hidden" name="doct_id" value="<?= $doctor['doct_Id'] ?>">

                                <label for="doctor-name">Doctor Name:</label>
                                <input type="text" name="doctor_name" value="<?= htmlspecialchars($doctor['doct_Name']) ?>"
                                    required>

                                <label for="doctor-email">Doctor Email:</label>
                                <input type="email" name="doctor_email"
                                    value="<?= htmlspecialchars($doctor['doct_Email']) ?>" required>

                                <label for="doctor-phone">Phone:</label>
                                <input type="number" name="doctor_phone"
                                    value="<?= htmlspecialchars($doctor['doct_Phone']) ?>" required>

                                <label for="doctor-password">Set New Password (leave blank to keep old):</label>
                                <input type="password" name="doctor_password" placeholder="••••••">

                                <label for="doctor-department">Department:</label>
                                <select name="doctor_department" required>
                                    <option disabled>Select your Department</option>
                                    <?php foreach ($departments as $deptId => $deptName): ?>
                                        <option value="<?= $deptId ?>" <?= $doctor['dept_Id'] == $deptId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($deptName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <label for="doctor-description">Specialist:</label>
                                <input type="text" name="doctor_description"
                                    value="<?= htmlspecialchars($doctor['doct_Speacialist']) ?>" required>

                                <button type="submit" onclick="doctorUpdated()">Update Doctor</button>
                            </form>
                        <?php endwhile; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="script/dashboard.js"></script>
    <script>
        allAdminHidden();
        document.getElementById('manageHospital').style.display = "flex"
    </script>

</body>

</html>