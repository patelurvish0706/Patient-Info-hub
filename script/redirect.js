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