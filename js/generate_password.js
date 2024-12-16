document.addEventListener("DOMContentLoaded", () => {
    const generatePasswordBtn = document.getElementById('generate-password-btn');
    const passwordField = document.getElementById('registered-password');
    const info = document.getElementById('password-info');

    if (generatePasswordBtn && passwordField && info) {
        generatePasswordBtn.addEventListener('click', function () {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let password = "";

            for (let i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }

            // Assign the password generated to the password field
            passwordField.value = password;

            // Show an informative message
            info.style.display = "block";

            // Hide the message after 3 seconds
            setTimeout(() => {
                info.style.display = "none";
            }, 3000);
        });
    }
});