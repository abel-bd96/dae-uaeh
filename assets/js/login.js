(function () {
    "use strict";

    const loginForm = document.getElementById("login-form");
    const passwordInput = document.getElementById("password");
    const passwordToggle = document.querySelector("[data-password-toggle]");
    const recoveryLink = document.querySelector("[data-recovery-link]");

    if (!loginForm || typeof swal !== "function") {
        return;
    }

    passwordToggle.addEventListener("click", function () {
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        passwordToggle.setAttribute("aria-pressed", String(isPassword));
        passwordToggle.setAttribute("aria-label", isPassword ? "Ocultar contraseña" : "Mostrar contraseña");
        passwordToggle.querySelector("i").className = isPassword ? "bi bi-eye-slash" : "bi bi-eye";
    });

    recoveryLink.addEventListener("click", function (event) {
        event.preventDefault();
        swal("Recuperar contraseña", "La recuperación de contraseña estará disponible próximamente.", "info");
    });

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const email = document.getElementById("email");
        const password = passwordInput;

        if (!email.value.trim() || !password.value) {
            swal("Campos incompletos", "Ingresa tu correo electrónico y contraseña para continuar.", "warning");
            return;
        }

        if (!email.checkValidity()) {
            swal("Correo no válido", "Ingresa un correo electrónico con formato válido.", "error");
            email.focus();
            return;
        }

        swal({
            title: "Inicio de sesión",
            text: "Credenciales válidas. Redirigiendo...",
            icon: "success",
            buttons: false,
            timer: 1200,
        }).then(function () {
            window.location.href = loginForm.dataset.redirectUrl;
        });
    });
})();
