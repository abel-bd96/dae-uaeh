/*  
TODO: Implementar la lógica de validación de credenciales y redirección real al servidor.
TODO: Mejorar la autenticación de la validación con Fetch API
*/

/* Utiliza IIFE para encapsular la lógica y evitar contaminación del espacio global */
(function () {
    "use strict";

    const loginForm = document.getElementById("login-form");
    const passwordInput = document.getElementById("password");
    const passwordToggle = document.querySelector("[data-password-toggle]");
    const recoveryLink = document.querySelector("[data-recovery-link]");

    /* Verifica si los elementos existen antes de agregar los event listeners */
    if (!loginForm || typeof swal !== "function") {
        return;
    }

    /* Agrega event listener para alternar la visibilidad de la contraseña */
    passwordToggle.addEventListener("click", function () {
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        passwordToggle.setAttribute("aria-pressed", String(isPassword));
        passwordToggle.setAttribute("aria-label", isPassword ? "Ocultar contraseña" : "Mostrar contraseña");
        passwordToggle.querySelector("i").className = isPassword ? "bi bi-eye-slash" : "bi bi-eye";
    });

    /* Agrega event listener para el enlace de recuperación de contraseña */
    recoveryLink.addEventListener("click", function (event) {
        event.preventDefault();
        swal("Recuperar contraseña", "La recuperación de contraseña estará disponible próximamente.", "info");
    });

    /* Agrega event listener para el envío del formulario de inicio de sesión */
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
