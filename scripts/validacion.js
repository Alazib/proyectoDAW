document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('registerForm');
    const nombreInput = document.getElementById('nombre');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    const nombreError = document.getElementById('nombreError');
    const usernameError = document.getElementById('usernameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    form.addEventListener('submit', function (e) {

        let valid = true;

        // Limpiar errores previos
        nombreError.textContent = '';
        usernameError.textContent = '';
        emailError.textContent = '';
        passwordError.textContent = '';
        confirmPasswordError.textContent = '';

        // Validar nombre
        if (nombreInput.value.trim() == '') {
            valid = false;
            nombreError.textContent = 'El campo nombre es obligatorio.';
        }

        // Validar usuario
        if (usernameInput.value.trim() === '') {
            valid = false;
            usernameError.textContent = 'El campo usuario es obligatorio.';
        }

        // Validar email
        if (emailInput.value.trim() === '') {
            valid = false;
            emailError.textContent = 'El campo correo electrónico es obligatorio.';
        } else if (!/\S+@\S+\.\S+/.test(emailInput.value)) {
            valid = false;
            emailError.textContent = 'Por favor, ingrese un correo electrónico válido.';
        }

        // Validar contraseña
        if (passwordInput.value.trim() === '') {
            valid = false;
            passwordError.textContent = 'El campo contraseña es obligatorio.';
        }

        // Validar confirmación de la contraseña
        if (confirmPasswordInput.value.trim() === '') {
            valid = false;
            confirmPasswordError.textContent = 'El campo confirmar contraseña es obligatorio.';
        } else if (confirmPasswordInput.value !== passwordInput.value) {
            valid = false;
            confirmPasswordError.textContent = 'Las contraseñas no coinciden.';
        }

        if (!valid) {
            e.preventDefault(); // Detener el envío del formulario si hay errores
        }
    });
});