// ----------- Check Width, Hide Long Title Name ----------------
const element = document.getElementById('pims');
element.style.display = 'none';

// -------------- Page redirect Buttons with Activepage navigation ----------------

function redirectHome() {
    window.location.href = "index.html";
}

function redirectLogin() {
    window.location.href = "login.html";
}

function redirectRegister() {
    window.location.href = "register.html";
}

function redirectManage() {
    window.location.href = "manage.html";
}

// --------------------- Manage --- Admin Login ? Register ----------------------

const adminLogin = document.getElementById("admin-login");
const adminRegister = document.getElementById("admin-register");

// adminLogin.style.display = "flex";
function admin_Register() {
    adminLogin.style.display = "none";
    adminRegister.style.display = "flex";
}

// adminRegister.style.display = "flex";
function admin_Login() {
    adminRegister.style.display = "none";
    adminLogin.style.display = "flex";
}

// -------------------- Manage --- Doctor Login ? Department ---------------------

const doctorLogin = document.getElementById("doctor-login");
const departmentLogin = document.getElementById("department-login");

function deprt_Login() {
    doctorLogin.style.display = "none";
    departmentLogin.style.display = "flex";
}

function doctor_Login() {
    departmentLogin.style.display = "none";
    doctorLogin.style.display = "flex";
}
