<?php if (isset($_SESSION['user_id'])): ?>

  <div class="container-fluid">
    <div class="row flex-nowrap">

      <!-- SIDEBAR -->
      <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark sidebar">
        <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-3 text-white">

          <ul class="nav nav-pills flex-column mb-auto align-items-center align-items-sm-start sidebar-menu">

            <!-- HOME -->
            <li class="nav-item">
              <a href="index.php" class="nav-link text-white px-0 sidebar-item">
                <i class="bi-house fs-5"></i>
                <span class="ms-2 d-none d-sm-inline">Inicio</span>
              </a>
            </li>

            <!-- PRODUCTOS (SOLO ADMIN) -->
            <?php if ($_SESSION['rol'] === 'Administrador'): ?>
              <li>
                <a href="index.php?page=productos" class="nav-link text-white px-0 sidebar-item">
                  <i class="bi-box-seam fs-5"></i>
                  <span class="ms-2 d-none d-sm-inline">Productos</span>
                </a>
              </li>
              <li>
                <a href="index.php?page=reportes" class="nav-link text-white px-0 sidebar-item">
                  <i class="bi bi-bar-chart-line fs-5"></i>
                  <span class="ms-2 d-none d-sm-inline">Gráficas</span>
                </a>
              </li>
              <li>
                <a href="index.php?page=informes" class="nav-link text-white px-0 sidebar-item">
                  <i class="bi bi-file-earmark-pdf fs-5"></i>
                  <span class="ms-2 d-none d-sm-inline">Informes</span>
                </a>
              </li>
              <li>
                <a href="index.php?page=compras" class="nav-link text-white px-0 sidebar-item">
                  <i class="bi bi-cart-check fs-5"></i>
                  <span class="ms-2 d-none d-sm-inline">Compras</span>
                </a>
              </li>
            <?php endif; ?>
            <!-- VENTAS (ADMIN Y VENDEDOR) -->
            <?php if (in_array($_SESSION['rol'], ['Administrador', 'Vendedor'])): ?>
              <li>
                <a href="index.php?page=ventas" class="nav-link text-white px-0 sidebar-item">
                  <i class="bi-cash fs-5"></i>
                  <span class="ms-2 d-none d-sm-inline">Ventas</span>
                </a>
              </li>
              <li>
                <a href="index.php?page=factura" class="nav-link text-white px-0 sidebar-item">
                  <i class="bi bi-calculator-fill fs-5"></i>
                  <span class="ms-2 d-none d-sm-inline">Calculos</span>
                </a>
              </li>
            <?php endif; ?>
          </ul>

        </div>
      </div>

      <!-- CONTENIDO -->
      <div class="col py-4">

      <?php endif; ?>