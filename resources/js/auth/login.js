document.addEventListener('DOMContentLoaded', function () {
    const toggleIcons = document.querySelectorAll('.toggle-password');

    toggleIcons.forEach(function (icon) {
        icon.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const passwordField = document.getElementById(targetId);

            if (passwordField) {
                const isPassword = passwordField.type === 'password';
                passwordField.type = isPassword ? 'text' : 'password';
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            }
        });
    });
});
