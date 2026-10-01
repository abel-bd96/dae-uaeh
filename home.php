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
</head>

<body class="d-flex flex-column min-vh-100 bg-secondary-uaeh"> <!-- Clases necesarias para mandar el footer al final siembre al fondo. -->

    <header class="bg-primary-uaeh text-white">
        <div class="container-fluid header__structure">
            <button class="menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Abrir menú">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
            <div class="header__brand">
                <a href="https://uaeh.edu.mx/" target="_blank" rel="noopener noreferrer">
                    <img src="./assets/img/logo_uaeh.png" alt="Logo de la Universidad Autónoma del Estado de Hidalgo y el Patronato Universitario">
                </a>
                <h1 class="fs-1">Dirección de Administración Escolar</h1>
            </div>
            <div class="header__spacer" aria-hidden="true"></div>
        </div>
    </header>

    <aside class="offcanvas offcanvas-start sidebar-menu" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header sidebar-menu__header">
            <h2 class="offcanvas-title" id="sidebarMenuLabel">Menú principal</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar menú"></button>
        </div>
        <div class="offcanvas-body">
            <nav aria-label="Navegación principal">
                <ul class="list-unstyled mb-0" id="mainMenuAccordion">

                    <!-- Inicio -->
                    <li>
                        <a class="sidebar-menu__link" href="./home.php">
                            <i class="bi bi-house sidebar-menu__icon" aria-hidden="true"></i>
                            Inicio
                        </a>
                    </li>

                    <!-- Trámites -->
                    <li class="sidebar-menu__group">
                        <button class="sidebar-menu__link sidebar-menu__trigger" type="button" data-bs-toggle="collapse" data-bs-target="#tramitesMenu" aria-expanded="false" aria-controls="tramitesMenu">
                            <i class="bi bi-file-earmark-text sidebar-menu__icon" aria-hidden="true"></i>
                            Trámites
                            <i class="bi bi-chevron-down sidebar-menu__caret" aria-hidden="true"></i>
                        </button>
                        <div class="collapse sidebar-menu__submenu" id="tramitesMenu">
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <button class="sidebar-menu__submenu-link sidebar-menu__trigger sidebar-menu__submenu-trigger" type="button" data-bs-toggle="collapse" data-bs-target="#constanciasMenu" aria-expanded="false" aria-controls="constanciasMenu">
                                        <i class="bi bi-folder2 sidebar-menu__submenu-icon" aria-hidden="true"></i>
                                        Constancias de Estudio
                                        <i class="bi bi-chevron-down sidebar-menu__caret" aria-hidden="true"></i>
                                    </button>
                                    <div class="collapse sidebar-menu__nested" id="constanciasMenu">
                                        <ul class="list-unstyled mb-0">
                                            <li>
                                                <a class="sidebar-menu__submenu-link sidebar-menu__submenu-link--nested" href="#administracion-constancias">
                                                    <i class="bi bi-file-earmark-check sidebar-menu__submenu-icon" aria-hidden="true"></i>
                                                    Administración de constancias
                                                </a>
                                            </li>
                                            <li>
                                                <a class="sidebar-menu__submenu-link sidebar-menu__submenu-link--nested" href="./modules/constancia-estudios/views/vtaCiclos.php">
                                                    <i class="bi bi-calendar2-week sidebar-menu__submenu-icon" aria-hidden="true"></i>
                                                    Planeación de Ciclos
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Configuración -->
                    <li class="sidebar-menu__group">
                        <button class="sidebar-menu__link sidebar-menu__trigger" type="button" data-bs-toggle="collapse" data-bs-target="#configuracionMenu" aria-expanded="false" aria-controls="configuracionMenu">
                            <i class="bi bi-gear sidebar-menu__icon" aria-hidden="true"></i>
                            Configuración
                            <i class="bi bi-chevron-down sidebar-menu__caret" aria-hidden="true"></i>
                        </button>
                        <div class="collapse sidebar-menu__submenu" id="configuracionMenu">
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <a class="sidebar-menu__submenu-link" href="#roles-permisos">
                                        <i class="bi bi-person-gear sidebar-menu__submenu-icon" aria-hidden="true"></i>
                                        Roles y Permisos
                                    </a>
                                </li>
                                <li>
                                    <a class="sidebar-menu__submenu-link" href="#casos-especiales">
                                        <i class="bi bi-exclamation-diamond sidebar-menu__submenu-icon" aria-hidden="true"></i>
                                        Casos Especiales
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="home-page flex-grow-1">
        <section class="container py-4 py-md-5" aria-labelledby="welcome-title">
            <div class="welcome-panel text-center">
                <div class="welcome-panel__icon" aria-hidden="true">
                    <i class="bi bi-hand-wave"></i>
                </div>
                <p class="welcome-panel__eyebrow mb-2">Dirección de Administración Escolar</p>
                <h2 id="welcome-title" class="display-6 mb-3">¡Bienvenido al sistema DAE!</h2>
                <p class="lead text-secondary mb-4">
                    Consulta los módulos disponibles desde el menú principal.
                </p>
                <a class="btn btn-primary-uaeh" href="#sidebarMenu" data-bs-toggle="offcanvas" aria-controls="sidebarMenu">
                    <i class="bi bi-grid me-2" aria-hidden="true"></i>
                    Ver módulos
                </a>
            </div>
        </section>
    </main>

    <footer class="d-flex flex-column align-items-center mt-auto text-white py-3 bg-primary-uaeh">
        <img src="./assets/img/garza.svg" alt="Logotipo Garza UAEH" class="mb-2" style="width: 30px; height: auto;">
        <p class="mb-0">Copyright © <?php echo date('Y'); ?> UAEH. Todos los derechos reservados</p>
        <p class="mt-0">Dirección de Administración Escolar</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>