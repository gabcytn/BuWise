document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const feedback = document.getElementById('passwordFeedback');

    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;

        const isLongEnough = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);

        if (password.length === 0) {
            feedback.textContent = '';
        } else if (!isLongEnough) {
            feedback.textContent = 'Password is too short (min. 8 characters)';
            feedback.style.color = '#ffdddd';
        } else if (!hasUppercase) {
            feedback.textContent = 'Password must include at least one uppercase letter';
            feedback.style.color = '#ffdddd';
        } else if (!hasLowercase) {
            feedback.textContent = 'Password must include at least one lowercase letter';
            feedback.style.color = '#ffdddd';
        } else if (!hasNumber) {
            feedback.textContent = 'Password must include at least one number';
            feedback.style.color = '#ffdddd';
        } else {
            feedback.textContent = 'Password looks good!';
            feedback.style.color = '#cde5fa';
        }
    });
});
