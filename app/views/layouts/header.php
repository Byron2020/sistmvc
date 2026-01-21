<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <link rel="stylesheet" href="app/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Distriuidora</title>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php"><text class="text-info fw-semibold">SPF </text>DISTRIBUIDORA</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="#">Rol: </a>
          </li>
          <?php if (isset($_SESSION['rol'])): ?>
            <li class="nav-item">
              <span class="nav-link disabled text-info fw-semibold">
                <?= htmlspecialchars($_SESSION['rol']) ?>
                <i class="bi bi-shield-lock me-1"></i>
              </span>
            </li>
          <?php endif; ?>

        </ul>
        <?php if (isset($_SESSION['user_id'])): ?>
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

            <li class="nav-item dropdown">

              <a class="nav-link dropdown-toggle d-flex align-items-center text-white"
                href="#"
                id="userDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <i class="bi bi-person-circle fs-5 me-2"></i>
                <?= htmlspecialchars($_SESSION['user_name']) ?>

              </a>

              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

                <li>
                  <a class="dropdown-item" href="index.php?view=perfil">
                    <i class="bi bi-person me-2"></i> Perfil
                  </a>
                </li>

                <li>
                  <hr class="dropdown-divider">
                </li>

                <li>
                  <a class="dropdown-item text-danger" href="index.php?action=logout">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                  </a>
                </li>

              </ul>
            </li>

          </ul>
        <?php endif; ?>

      </div>
    </div>
  </nav>
  <!-- body-->