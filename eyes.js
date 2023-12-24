// eyes.js
document.addEventListener("DOMContentLoaded", function () {
    let eyeicons = document.querySelectorAll("#eyeicon");
    let passwords = document.querySelectorAll('input[type="password"]');

    eyeicons.forEach(function (eyeicon, index) {
        eyeicon.addEventListener("click", function () {
            togglePasswordVisibility(passwords[index], eyeicon);
        });
    });

    function togglePasswordVisibility(input, eyeicon) {
        if (input.type === "password") {
            input.type = "text";
            eyeicon.classList.remove("fa-eye-slash");
            eyeicon.classList.add("fa-eye");
        } else {
            input.type = "password";
            eyeicon.classList.remove("fa-eye");
            eyeicon.classList.add("fa-eye-slash");
        }
    }
});
