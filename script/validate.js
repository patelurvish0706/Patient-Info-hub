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

        // ✅ Submit the form manually
        form.submit();
}

// Register

function validateAdminReg(event) {
    event.preventDefault();

    const email = document.getElementById("register-admin-email").value.trim();
    const password = document.getElementById("register-admin-password").value.trim();
    const repassword = document.getElementById("register-admin-repassword").value.trim();

    // Simple validation
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
    this.submit();

}

function validateHospitalSub(event){
    event.preventDefault();

        const name = document.getElementById("hospital-name").value.trim();
        const openTime = document.getElementById("hospital-time-open").value;
        const closeTime = document.getElementById("hospital-time-close").value;
        const phone = document.getElementById("hospital-phone").value.trim();
        const lat = document.getElementById("hospital-latitude").value.trim();
        const long = document.getElementById("hospital-longitude").value.trim();
        const email = document.getElementById("hospital-email").value.trim();

        let errorMessages = [];

        // Validate hospital name
        if (name === "") {
            errorMessages.push("Hospital name is required.");
        }

        // Validate time
        if (!openTime || !closeTime) {
            errorMessages.push("Both opening and closing times are required.");
        } else if (openTime >= closeTime) {
            errorMessages.push("Closing time must be after opening time.");
        }

        // Validate phone number
        const phonePattern = /^[0-9]{10}$/;
        if (!phonePattern.test(phone)) {
            errorMessages.push("Phone number must be exactly 10 digits.");
        }

        // Validate latitude
        if (isNaN(lat) || lat < -90 || lat > 90) {
            errorMessages.push("Latitude must be a number between -90 and 90.");
        }

        // Validate longitude
        if (isNaN(long) || long < -180 || long > 180) {
            errorMessages.push("Longitude must be a number between -180 and 180.");
        }

        // If errors, prevent form submission and show alerts
        if (errorMessages.length > 0) {
            e.preventDefault();
            alert(errorMessages.join("\n"));
        }

        const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/;
        if (!emailPattern.test(email)) {
            alert("Enter a valid hospital email.");
            e.preventDefault();
            return;
        }

        alert("Details Updated.")
}
