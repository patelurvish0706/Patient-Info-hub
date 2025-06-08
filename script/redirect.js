// ----------- Check Width, Hide Long Title Name ----------------
const element = document.getElementById('pims');
element.style.display = 'none';


// function checkWidth() {
//     const element = document.getElementById('pims');
//     if (window.innerWidth < 700) {
//         element.style.display = 'none';
//     } else {
//         element.style.display = 'flex';
//     }
// }

// // Run on load
// checkWidth();

// // Run on resize
// window.addEventListener('resize', checkWidth);

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

// -------------------- Login ---------------------