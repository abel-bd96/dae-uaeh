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
					<a class="sidebar-menu__link" href="../../../home.php">
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
											<a class="sidebar-menu__submenu-link sidebar-menu__submenu-link--nested" href="./vtaCiclos.php">
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