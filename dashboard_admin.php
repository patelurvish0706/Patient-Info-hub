<?php
session_start();
if (!isset($_SESSION['admin_Id'])) {
    header("Location: manage.html");
    exit;
}
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
        #manageHospital{
            flex-direction:column;
        }

        #map{
            width: 100% ;
            height : 300px;
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

            <div id="manageHospital" style="display: flex;">
                <h2>Manage Hospital</h2>
                <div id="listApps">
                    <div id="newHospt" class="appTabNames">New Hospital</div>
                    <div id="existing" class="appTabNames">Existing</div>
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
                        $hospitalName  = "";
                        $hospitalEmail = "";
                        $timeOpen      = "";
                        $timeClose     = "";
                        $phone         = "";
                        $lat           = "";
                        $long          = "";
                        
                        // Check if data already exists
                        $stmt = $conn->prepare("SELECT * FROM hospitals WHERE admin_Id = ?");
                        $stmt->bind_param("i", $adminId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            $hospital = $result->fetch_assoc();
                        
                            $hospitalName  = $hospital['hospital_Name'];
                            $hospitalEmail = $hospital['hospital_Email'];
                            $timeOpen      = $hospital['hospital_Time_open'];
                            $timeClose     = $hospital['hospital_Time_close'];
                            $phone         = $hospital['hospital_Phone'];
                            $lat           = $hospital['hospital_Lat'];
                            $long          = $hospital['hospital_Long'];
                        }
                        
                        $conn->close();
                        ?>
                        <form action="script/submit_hospital.php" method="post">
                            <p>Enter Hospital Details.</p>

                            <label for="hospital-name">Hospital Name:</label>
                            <input type="text" name="hospital-name" id="hospital-name" placeholder="The Great Civil Hospital, Ahmedabad"
                                   value="<?= htmlspecialchars($hospitalName) ?>" required>

                            <label for="hospital-email">Hospital Email:</label>
                            <input type="email" name="hospital-email" id="hospital-email" placeholder="hospital@email.com"
                                   value="<?= htmlspecialchars($hospitalEmail) ?>" required>

                            <label>Time:</label>
                            Open at <input type="time" name="hospital-time-open" id="hospital-time-open"
                                           value="<?= $timeOpen ?>" required>
                            Close at <input type="time" name="hospital-time-close" id="hospital-time-close"
                                            value="<?= $timeClose ?>" required>

                            <label for="hospital-phone">Phone:</label>
                            <input type="number" name="hospital-phone" id="hospital-phone" placeholder="9876543210"
                                   value="<?= htmlspecialchars($phone) ?>" required>

                            <label for="hospital-loc">Select Location:</label>
                            <div id="map" name="hospital-loc"></div>

                            <label for="hospital-latitude">Hospital Latitude</label>
                            <input type="number" name="hospital-latitude" id="hospital-latitude" step="any"
                                   value="<?= $lat ?>" required>

                            <label for="hospital-longitude">Hospital Longitude</label>
                            <input type="number" name="hospital-longitude" id="hospital-longitude" step="any"
                                   value="<?= $long ?>" required>

                            <button type="submit" onclick="validateHospitalSub(event);">Save Details</button>
                        </form>
                    </div>

                    <div id="existing-content">

                    </div>

                </div>
            </div>
            
            <div id="manageDepartment">
                <h2>Manage Department</h2>
            </div> 
                
            <div id="manageDoctor">
                <h2>Manage Doctors</h2>
            </div>
        </div>
    </section>

    <script src="script/dashboard.js"></script>
    <script>
        allAdminHidden();
        document.getElementById('manageHospital').style.display = "flex"
    </script>
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

            map.on('click', function(e) {
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
</body>

</html>

