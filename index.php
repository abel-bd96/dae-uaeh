<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Web de la Dirección de Administración Escolar - UAEH</title>
    <meta name="description" content="Sistema web de la Dirección de Administración Escolar de la Universidad Autónoma del Estado de Hidalgo">
    <meta name="keywords" content="UAEH, Universidad Autónoma del Estado de Hidalgo, Dirección de Administración Escolar, Sistema web">
    <!-- Links -->
    <link rel="shortcut icon" href="./assets/img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="preload" href="./assets/css/styles.css" as="style">
    <link rel="stylesheet" href="./assets/css/styles.css">
    <!-- Sweetalert -->
    <link rel="stylesheet" href="../../generalesDIyS/_estilo/sweetalert.css" />
</head>

<body class="d-flex flex-column min-vh-100 bg-secondary-uaeh"> <!-- Clases necesarias para mandar el footer al final siembre al fondo. -->

    <header class="bg-primary-uaeh text-white">
        <div class="container-fluid header__structure">
            <div class="header__spacer" aria-hidden="true"></div>
            <div class="header__brand">
                <a href="https://uaeh.edu.mx/" target="_blank" rel="noopener noreferrer">
                    <img src="./assets/img/logo_uaeh.png" alt="Logo de la Universidad Autónoma del Estado de Hidalgo y el Patronato Universitario">
                </a>
                <h1 class="fs-1">Dirección de Administración Escolar</h1>
            </div>
            <div class="header__spacer" aria-hidden="true"></div>
        </div>
    </header>

    <main class="login-page flex-grow-1">
        <section class="container py-4 py-md-5" aria-labelledby="login-title">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                    <div class="login-card bg-white shadow-sm">
                        <div class="login-card__heading text-center">
                            <span class="login-card__icon" aria-hidden="true">
                                <i class="bi bi-person-lock"></i>
                            </span>
                            <p class="login-card__eyebrow mb-2">Acceso al sistema DAE</p>
                            <h2 id="login-title" class="h3 mb-2">Iniciar sesión</h2>
                            <p class="text-secondary mb-0">Ingresa tus credenciales para continuar</p>
                        </div>

                        <form id="login-form" class="login-form" data-redirect-url="./home.php" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text" aria-hidden="true"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" autocomplete="email" placeholder="nombre@uaeh.edu.mx" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text" aria-hidden="true"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" placeholder="Ingresa tu contraseña" required>
                                    <button class="btn btn-outline-secondary password-toggle" type="button" aria-label="Mostrar contraseña" aria-pressed="false" data-password-toggle>
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="text-end mb-4">
                                <a class="login-form__link" href="#recuperar-contrasena" data-recovery-link>¿Olvidaste tu contraseña?</a>
                            </div>

                            <button type="submit" class="btn btn-primary-uaeh w-100">
                                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>
                                Iniciar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="d-flex flex-column align-items-center mt-auto text-white py-3 bg-primary-uaeh">
        <img src="./assets/img/garza.svg" alt="Logotipo Garza UAEH" class="mb-2" style="width: 30px; height: auto;">
        <p class="mb-0">Copyright © <?php echo date('Y'); ?> UAEH. Todos los derechos reservados</p>
        <p class="mt-0">Dirección de Administración Escolar</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="./assets/js/login.js"></script>

</body>

</html>