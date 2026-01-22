<?php
include('app/config/session.php');
include('app/helpers/session.php');
include('app/helpers/auth.php');

/* ===  ESTADO DE SESIÓN ===  ===  ===  ===  ===  == */
$logged = isset($_SESSION['user_id']);

// ========== CLIENTES - POST / DELETE ==========================
if (isset($_GET['page'], $_GET['action']) && $_GET['page'] === 'clientes') {

    require_once 'app/controllers/ClienteController.php';
    $controller = new ClienteController();

    if ($_GET['action'] === 'destroy') {
        $controller->destroy();
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ($_GET['action'] === 'create') {
            $controller->store();
            exit;
        }

        if ($_GET['action'] === 'edit') {
            $controller->update();
            exit;
        }
    }
}


// ========== PRODUCTOS - POST / DELETE =====================================
if (isset($_GET['page'], $_GET['action']) && $_GET['page'] === 'productos') {

    require_once 'app/controllers/ProductController.php';
    $controller = new ProductController();

    if ($_GET['action'] === 'delete') {
        $controller->destroy();
        exit;
    }
}

// ============== PRODUCTOS - POST (CREATE / EDIT) ==========================
if (
    isset($_GET['page'], $_GET['action']) &&
    $_GET['page'] === 'productos' &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    require_once 'app/controllers/ProductController.php';
    $controller = new ProductController();

    if ($_GET['action'] === 'create') {
        $controller->create();
        exit;
    }

    if ($_GET['action'] === 'edit') {
        $controller->edit();
        exit;
    }
}

// ============  PROCESAR Compra POST ANTES DE HTML ==================
if (
    isset($_GET['page'], $_GET['action']) && $_GET['page'] === 'compras'
) {
    require_once 'app/controllers/CompraController.php';
    $controller = new CompraController();
    if ($_GET['action'] === 'store') {
        $controller->store();
        exit;
    }

    if ($_GET['action'] === 'anular') {
        $controller->anular();
        exit;
    }
}
// ===============================
// VENTAS - PDF (ANTES DEL HTML)
// ===============================
if (
    isset($_GET['page'], $_GET['action']) &&
    $_GET['page'] === 'ventas' &&
    $_GET['action'] === 'pdf'
) {
    require_once 'app/controllers/VentaController.php';
    $controller = new VentaController();
    $controller->pdf();
    exit;
}
// ===============================
// VENTAS - POST / ANULAR
// ===============================
if (isset($_GET['page'], $_GET['action']) && $_GET['page'] === 'ventas') {

    require_once 'app/controllers/VentaController.php';
    $controller = new VentaController();

    if ($_GET['action'] === 'store') {
        $controller->store();
        exit;
    }

    if ($_GET['action'] === 'anular') {
        $controller->anular();
        exit;
    }
}


/* ===  ===  RUTEO PRINCIPAL===  ===  ===  ===  == */
$page = $_GET['page'] ?? 'dashboard';

// Si NO está logueado → forzar login
if (!$logged) {
    $page = 'login';
}
/* ===  === ACCIONES ( LOGIN / LOGOUT ) ===  ===  ===  == */
if (isset($_GET['action'])) {

    include 'app/controllers/AuthController.php';
    $auth = new AuthController();

    if ($_GET['action'] === 'login') {
        $auth->login();
        exit;
    }

    if ($_GET['action'] === 'logout') {
        $auth->logout();
        exit;
    }
}
// ===============================
// REPORTES - PDF (ANTES DEL HTML)
// ===============================
if (
    isset($_GET['page'], $_GET['action']) &&
    $_GET['page'] === 'reportes' &&
    $_GET['action'] === 'pdf'
) {
    require_once 'app/controllers/ReporteController.php';
    $controller = new ReporteController();
    $controller->pdf();
    exit;
}
// ===============================
// Informes datos - PDF (ANTES DEL HTML)
// ===============================
if (
    isset($_GET['page'], $_GET['action']) &&
    $_GET['page'] === 'informes' &&
    $_GET['action'] === 'pdf'
) {
    require_once 'app/controllers/InformeController.php';
    $controller = new InformeController();
    $controller->ipdf();
    exit;
}
// ======= FACTURA - CALCULAR (POST) =======================
if (
    isset($_GET['page'], $_GET['action']) &&
    $_GET['page'] === 'factura' &&
    $_GET['action'] === 'calcular' &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    require_once 'app/controllers/FacturaController.php';
    $controller = new FacturaController();
    $controller->calcular();
}

/********************* Header *****************************/
include 'app/views/layouts/header.php';

if ($logged) {
    include 'app/views/layouts/menu.php';
}

/* ===  ===  === CONTENIDO ===  ===  === */
switch ($page) {

    case 'login':
        include 'app/views/auth/login.php';
        break;

    case 'productos':
        role_required(['Administrador']);
        require_once 'app/controllers/ProductController.php';

        $controller = new ProductController();
        $action = $_GET['action'] ?? 'index';

        if ($action === 'create') {
            include 'app/views/products/create_product.php';
        } elseif ($action === 'edit') {
            $controller->edit();
        } else {
            $controller->index();
        }
        break;
    case 'clientes':
        role_required(['Administrador']);
        require_once 'app/controllers/ClienteController.php';

        $controller = new ClienteController();
        $action = $_GET['action'] ?? 'index';

        if ($action === 'create') {
            $controller->create();
        } elseif ($action === 'edit') {
            $controller->edit();
        } else {
            $controller->index();
        }
        break;
    case 'compras':
        role_required(['Administrador']);
        require_once 'app/controllers/CompraController.php';

        $controller = new CompraController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'create':
                $controller->create();
                break;

            case 'show':
                $controller->show();
                break;

            default:
                $controller->index();
        }
        break;
    case 'factura':
        role_required(['Administrador', 'Vendedor']);
        require_once 'app/controllers/FacturaController.php';

        $controller = new FacturaController();
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'resultado':
                $controller->resultado();
                break;

            default:
                $controller->index();
                break;
        }
        break;
    case 'ventas':
        role_required(['Administrador', 'Vendedor']);
        require_once 'app/controllers/VentaController.php';

        $controller = new VentaController();
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'create':
                $controller->create();
                break;

            case 'show':
                $controller->show();
                break;

            default:
                $controller->index();
                break;
        }
        break;
    case 'reportes':
        role_required(['Administrador']);

        require_once 'app/controllers/ReporteController.php';
        $controller = new ReporteController();

        $action = $_GET['action'] ?? 'index';

        switch ($action) {

            case 'grafica':
                $controller->grafica();
                break;

            default:
                //$controller->index();
                include 'app/views/reportes/index.php';
                break;
        }
        break;
    case 'informes':
        role_required(['Administrador', 'Vendedor']);
        require_once 'app/controllers/InformeController.php';

        $controller = new InformeController();
        $action = $_GET['action'] ?? 'index';

        switch ($action) {

            case 'iproductos':
                $controller->iproductos();
                break;

            case 'icompras':
                $controller->icompras();
                break;

            case 'iventas':
                $controller->iventas();
                break;

            case 'pdf':
                $controller->ipdf();
                break;

            default:
                $controller->index();
                break;
        }
        break;
    default:
        include 'app/views/dashboard/index.php';
}

/* ====== FOOTER =============== */
include 'app/views/layouts/footer.php';
