const tabButtons = document.querySelectorAll('.tabButton');

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active from all
        tabButtons.forEach(btn => btn.classList.remove('active'));
        // Add active to clicked one
        button.classList.add('active');
    });
});

// -------------------- DashBoard --- Flex Data and None other Tabs ---------------- 

function allAdminHidden() {
    document.getElementById('manageHospital').style.display = "none"
    document.getElementById('manageDepartment').style.display = "none"
    document.getElementById('manageDoctor').style.display = "none"
}

function allUserHidden(){
    document.getElementById('myDetails').style.display = "none"
    document.getElementById('bookAppointment').style.display = "none"
    document.getElementById('trackAppointment').style.display = "none"
    document.getElementById('reports').style.display = "none"
    document.getElementById('prescriptions').style.display = "none"
}

function switchTabAdmin(tab) {
    switch (tab) {
        case 'mngHsptl':
            allAdminHidden();
            document.getElementById('manageHospital').style.display = "flex"
            break;

        case 'mngDprtmnt':
            allAdminHidden();
            document.getElementById('manageDepartment').style.display = "flex"
            break;

        case 'mngDctrs':
            allAdminHidden();
            document.getElementById('manageDoctor').style.display = "flex"
            break;

        default:
            break;
    }
}

function switchTabUser(tab) {
    switch (tab) {
        case 'myDetails':
            allUserHidden();
            document.getElementById('myDetails').style.display = "flex"
            break;

        case 'BookAppoint':
            allUserHidden();
            document.getElementById('bookAppointment').style.display = "flex"
            break;

        case 'trackAppoint':
            allUserHidden();
            document.getElementById('trackAppointment').style.display = "flex"
            break;

        case 'Reports':
            allUserHidden();
            document.getElementById('reports').style.display = "flex"
            break;

        case 'Prescrip':
            allUserHidden();
            document.getElementById('prescriptions').style.display = "flex"
            break;

        default:
            break;
    }
}

// LogOut

function LogoutAdmin() {
    // Redirect to PHP logout script
    window.location.href = "admin_logout.php";
}

