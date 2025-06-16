// --- Manage.html ---

/* Admin login */
function validateAdminLog(event) {
    const form = document.getElementById("admin-login");
    event.preventDefault();

    const email = document.getElementById("login-admin-email").value.trim();
    const password = document.getElementById("login-admin-password").value.trim();

    if (!email || !password) {
        alert("Please fill in all fields.");
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    form.submit();
}

/* Admin Register */
function validateAdminReg(event) {
    const form = document.getElementById("admin-register");
    event.preventDefault();

    const email = document.getElementById("register-admin-email").value.trim();
    const password = document.getElementById("register-admin-password").value.trim();
    const repassword = document.getElementById("register-admin-repassword").value.trim();

    if (!email || !password || !repassword) {
        alert("Please fill in all fields.");
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters.");
        return;
    }

    if (password !== repassword) {
        alert("Passwords do not match.");
        return;
    }

    // Submit form if all checks pass
    form.submit();

    alert("Admin Registered Successfully.✅");
}

/* Doctor Login */
function validateDocLog(event) {
    const form = document.getElementById("doctor-login");
    event.preventDefault();

    const email = document.getElementById("login-doctor-email").value.trim();
    const password = document.getElementById("login-doctor-password").value.trim();

    if (!email || !password) {
        alert("Please fill in both email and password.");
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    if (password.length < 5) {
        alert("Invalid Password");
        return;
    }

    form.submit();
}

/* Department Login */
function validateDeptLog(event) {
    const form = document.getElementById("department-login");
    event.preventDefault();

    const email = document.getElementById("login-deprt-email").value.trim();
    const password = document.getElementById("login-deprt-password").value.trim();

    if (!email || !password) {
        alert("Please fill in both email and password.");
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    if (password.length < 5) {
        alert("Invalid Password");
        return;
    }

    form.submit();
}

// --- Dashboard_Admin.php ---

/* Hospital Submit */
function validateHospitalSub(event) {
    const form = document.getElementById("Hospital-info-submit");
    event.preventDefault();

    const name = document.getElementById("hospital-name").value.trim();
    const openTime = document.getElementById("hospital-time-open").value;
    const closeTime = document.getElementById("hospital-time-close").value;
    const phone = document.getElementById("hospital-phone").value.trim();
    const lat = document.getElementById("hospital-latitude").value.trim();
    const long = document.getElementById("hospital-longitude").value.trim();
    const email = document.getElementById("hospital-email").value.trim();

    // Validate hospital name
    if (name === "") {
        alert("Hospital name is required.");
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Enter a valid hospital email.");
        return;
    }

    // Validate time
    if (!openTime || !closeTime) {
        alert("Both opening and closing times are required.");
        return;
    } else if (openTime >= closeTime) {
        alert("Closing time must be after opening time.");
        return;
    }

    // Validate phone number
    const phonePattern = /^[0-9]{10}$/;
    if (!phonePattern.test(phone)) {
        alert("Phone number must be required with exactly 10 digits.");
        return;
    }

    // Validate latitude
    if (isNaN(lat) || lat < -90 || lat > 90) {
        alert("Latitude must be a number between -90 and 90.");
        return;
    }

    // Validate longitude
    if (isNaN(long) || long < -180 || long > 180) {
        alert("Longitude must be a number between -180 and 180.");
        return;
    }

    form.submit();
    alert("Hospital Details Saved Successfully.✅")
}

/* Department Submit */
function validateDepartmentSub(event) {
    const form = document.getElementById("department-info-submit");
    event.preventDefault();

    const name = document.getElementById("department-name").value.trim();
    const email = document.getElementById("department-email").value.trim();
    const phone = document.getElementById("department-phone").value.trim();
    const password = document.getElementById("department-password").value.trim();
    const description = document.getElementById("department-description").value.trim();

    if (!name || !email || !phone || !password || !description) {
        alert("Please fill in all fields.");
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return;
    }

    if (phone.length < 7 || phone.length > 15) {
        alert("Please enter a valid phone number.");
        return;
    }

    form.submit();
    alert("Department Details Saved Successfully.✅")
    switchTabAdmin('mngDprtmnt');
}

/* Doctor Submit */
function validateDoctorSub(event) {
    const form = document.getElementById("doctor-info-submit");
    event.preventDefault();

    const name = document.getElementById("doctor-name").value.trim();
    const email = document.getElementById("doctor-email").value.trim();
    const phone = document.getElementById("doctor-phone").value.trim();
    const password = document.getElementById("doctor-password").value.trim();
    const department = document.getElementById("doctor-department").value;
    const description = document.getElementById("doctor-description").value.trim();

    if (!name || !email || !phone || !password || !department || !description) {
        alert("Please fill in all fields.");
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return;
    }

    if (phone.length < 7 || phone.length > 15) {
        alert("Please enter a valid phone number.");
        return;
    }

    // ✅ All validations passed
    form.submit();
    alert("Doctor Profile Saved Successfully ✅");
    switchTabAdmin('mngDctrs');
}

/* Department Update */
function departmentUpdated() {
    switchTabAdmin('mngDprtmnt');
    alert('Department Information is updated successfully.✅');
}

/* Doctor Update */
function doctorUpdated() {
    switchTabAdmin('mngDctrs');
    alert('Doctor Information is updated successfully.✅');
}

// --- Register.html ---

/* User Register */
function validateUserRegister(event) {
    const form = document.getElementById("user-reg-submit");
    event.preventDefault();

    const name = document.getElementById("reg-user-name").value.trim();
    const dob = document.getElementById("reg-user-dob").value;
    const gender = document.getElementById("reg-user-gender").value;
    const phone = document.getElementById("reg-user-phone").value.trim();
    const address = document.getElementById("reg-user-address").value.trim();
    const email = document.getElementById("reg-user-email").value.trim();
    const password = document.getElementById("reg-user-password").value.trim();
    const repassword = document.getElementById("reg-user-repassword").value.trim();

    if (!name || !dob || !gender || !phone || !address || !email || !password || !repassword) {
        alert("Please fill in all fields.");
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return;
    }

    if (password !== repassword) {
        alert("Passwords do not match.");
        return;
    }

    if (phone.length < 7 || phone.length > 15) {
        alert("Please enter a valid phone number.");
        return;
    }

    form.submit();
    alert("You're Registered Successfully. Login with Email and Password.✅")
}

// --- Dashboard_User.php ---

/* User Details Update */
function validateUserDetUpdated(event) {
    const form = document.getElementById("user-det-update");
    event.preventDefault();

    const name = document.getElementById("reg-user-name").value.trim();
    const dob = document.getElementById("reg-user-dob").value;
    const gender = document.getElementById("reg-user-gender").value;
    const phone = document.getElementById("reg-user-phone").value.trim();
    const address = document.getElementById("reg-user-address").value.trim();

    if (!name || !dob || !gender || !phone || !address) {
        alert("Please fill in all fields.");
        return;
    }

    // Future DOB not allowed
    const today = new Date();
    const selectedDOB = new Date(dob);
    if (selectedDOB > today) {
        alert("Date of birth cannot be in the future.");
        return;
    }

    // Phone number length check
    if (phone.length < 8 || phone.length > 13) {
        alert("Please enter a valid phone number.");
        return;
    }

    form.submit();
    alert('Your Personal details are updated successfully.✅');
    switchTabUser('myDetails');
}

/* User Appointment Form */
function validateAppSub(event) {
    const form = document.getElementById("user-app-book");
    event.preventDefault();

    const appDate = document.getElementById("app_date").value;
    const appTime = document.getElementById("app_time").value;
    const reason = document.getElementById("visit_for").value.trim();

    if (!appDate || !appTime || !reason) {
        alert("Please fill in all fields.");
        return;
    }

    // Check if appointment date is in the past
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time part
    const selectedDate = new Date(appDate);

    if (selectedDate < today) {
        alert("Appointment date cannot be in the past.");
        return;
    }

    form.submit();
    alert('Your Appointment is Booked Successfully.✅');

    document.getElementById('department-list').style.display = 'block';
    document.getElementById('appointment-form').style.display = 'none';
}

// --- Dashboard_Doctor.php ---

// Submiting Report 
function validateRepSub(event) {
    const form = document.getElementById("reportForm");
    event.preventDefault();

    const outcome = form.querySelector('textarea[name="checkup_outcome"]').value.trim();
  const prescriptions = form.querySelector('textarea[name="prescriptions"]').value.trim();
  const suggestion = form.querySelector('textarea[name="suggestion"]').value.trim();

  if (!outcome || !prescriptions || !suggestion) {
    alert("Please fill in all the fields.");
    return;
  }

  // Show alert, then submit
  alert("Patient Report Submitted Successfully.✅");

  // Small delay to allow alert to finish before submitting
  setTimeout(() => {
    form.submit();
  }, 50);
  
}

// Updating report
function validateUpdateRepDoc(event) {
    event.preventDefault(); // Stop default form submission

    const form = document.getElementById("update-rep-doc");

    const outcome = form.querySelector('textarea[name="checkup_outcome"]').value.trim();
    const prescriptions = form.querySelector('textarea[name="prescriptions"]').value.trim();
    const suggestion = form.querySelector('textarea[name="suggestion"]').value.trim();

    // Basic field validation
    if (!outcome || !prescriptions || !suggestion) {
        alert("All fields are required.");
        return;
    }

    // Optionally: you can add more detailed checks here

    alert("Report updated successfully.✅");

    // Slight delay to ensure alert is seen before submission
    setTimeout(() => {
        form.submit();
    }, 50);
}