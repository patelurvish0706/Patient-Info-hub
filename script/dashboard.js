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

function allUserHidden() {
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

// Logout

function LogoutAdmin() {
    window.location.href = "script/admin_logout.php";
}

function LogoutUser(){
    window.location.href = "script/user_logout.php";
}

function dataUpdated() {
    switchTabAdmin('mngDprtmnt');
    alert('Your Department Information is updated successfully.');
}

// ADDING and UPDATING -> DEPARTMENT

function showAddDepartment() {

    document.getElementById('listDepts').style.backgroundColor = '#FFF';
    document.getElementById('listDepts').style.color = '#000';

    document.getElementById('addDepts').style.backgroundColor = '#1E90FF';
    document.getElementById('addDepts').style.color = '#FFF';

    document.getElementById('addDepts-content').style.display = 'flex';
    document.getElementById('listDepts-content').style.display = 'none';

}

function showListDepartment() {
    document.getElementById('listDepts').style.backgroundColor = '#1E90FF';
    document.getElementById('listDepts').style.color = '#FFF';

    document.getElementById('addDepts').style.backgroundColor = '#FFF';
    document.getElementById('addDepts').style.color = '#000';

    document.getElementById('addDepts-content').style.display = 'none';
    document.getElementById('listDepts-content').style.display = 'flex';
}

// ADDING and UPDATING -> DOCTORS

function showAddDoctor() {

    document.getElementById('listDoctor').style.backgroundColor = '#FFF';
    document.getElementById('listDoctor').style.color = '#000';

    document.getElementById('addDoctor').style.backgroundColor = '#1E90FF';
    document.getElementById('addDoctor').style.color = '#FFF';

    document.getElementById('addDoctor-content').style.display = 'flex';
    document.getElementById('listDoctor-content').style.display = 'none';

}
function showListDoctors(){

    document.getElementById('listDoctor').style.backgroundColor = '#1E90FF';
    document.getElementById('listDoctor').style.color = '#FFF';

    document.getElementById('addDoctor').style.backgroundColor = '#FFF';
    document.getElementById('addDoctor').style.color = '#000';

    document.getElementById('addDoctor-content').style.display = 'none';
    document.getElementById('listDoctor-content').style.display = 'flex';
}