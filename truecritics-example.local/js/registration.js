document.addEventListener('DOMContentLoaded', function () {
    const agreeCheckbox = document.getElementById('agree');
    const submitBtn = document.getElementById('submitBtn');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    function checkPasswords() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (password !== confirmPassword) {
            confirmPasswordInput.setCustomValidity("Пароли не совпадают");
            return false;
        } else {
            confirmPasswordInput.setCustomValidity("");
            return true;
        }
    }

    function updateSubmitButton() {
        submitBtn.disabled = !(agreeCheckbox.checked && checkPasswords());
    }

    // События для активации кнопки
    agreeCheckbox.addEventListener('change', updateSubmitButton);
    passwordInput.addEventListener('input', updateSubmitButton);
    confirmPasswordInput.addEventListener('input', updateSubmitButton);

    updateSubmitButton();
});