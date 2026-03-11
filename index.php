<?php
ob_start();
require_once 'app/config/session.php';
require_once 'app/helpers/session.php';
require_once 'app/helpers/auth.php';

/* ==========================================================
   1️⃣  ACCIONES LOGIN / LOGOUT (PRIMERO SIEMPRE)
========================================================== */
if (isset($_GET['action'])) {

    require_once 'app/controllers/AuthController.php';
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

/* ==========================================================
   2️⃣  ESTADO REAL DE SESIÓN
========================================================== */
$logged = isset($_SESSION['user_id']);

/* ==========================================================
   3️⃣  DEFINIR PÁGINA
========================================================== */
$page = $_GET['page'] ?? 'home';

/* ==========================================================
   4️⃣  REDIRECCIÓN AUTOMÁTICA
========================================================== */
if ($logged && $page === 'home') {
    header("Location: index.php?page=reportes");
    exit;
}

$public_pages = ['home', 'login'];

if (!$logged && !in_array($page, $public_pages)) {
    header("Location: index.php");
    exit;
}

/* ==========================================================
   5️⃣  PROCESOS ANTES DE HTML (POST / DELETE / PDF)
========================================================== */

/* ===== CLIENTES ===== */
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

/* ===== PRODUCTOS ===== */
if (isset($_GET['page'], $_GET['action']) && $_GET['page'] === 'productos') {

    require_once 'app/controllers/ProductController.php';
    $controller = new ProductController();

    if ($_GET['action'] === 'delete') {
        $controller->destroy();
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ($_GET['action'] === 'create') {
            $controller->create();
            exit;
        }

        if ($_GET['action'] === 'edit') {
            $controller->edit();
            exit;
        }
    }
}

/* ===== COMPRAS ===== */
if (isset($_GET['page'], $_GET['action']) && $_GET['page'] === 'compras') {

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

/* ===== VENTAS ===== */
if (isset($_GET['page'], $_GET['action']) && $_GET['page'] === 'ventas') {

    require_once 'app/controllers/VentaController.php';
    $controller = new VentaController();

    if ($_GET['action'] === 'pdf') {
        $controller->pdf();
        exit;
    }

    if ($_GET['action'] === 'store') {
        $controller->store();
        exit;
    }

    if ($_GET['action'] === 'anular') {
        $controller->anular();
        exit;
    }
}

/* ===== REPORTES PDF ===== */
if (isset($_GET['page'], $_GET['action']) &&
    $_GET['page'] === 'reportes' &&
    $_GET['action'] === 'pdf') {

    require_once 'app/controllers/ReporteController.php';
    $controller = new ReporteController();
    $controller->pdf();
    exit;
}

/* ===== INFORMES PDF ===== */
if (isset($_GET['page'], $_GET['action']) &&
    $_GET['page'] === 'informes' &&
    $_GET['action'] === 'pdf') {

    require_once 'app/controllers/InformeController.php';
    $controller = new InformeController();
    $controller->ipdf();
    exit;
}

/* ==========================================================
   6️⃣  HEADER
========================================================== */
include 'app/views/layouts/header.php';

if ($logged) {
    include 'app/views/layouts/menu.php';
}

/* ==========================================================
   7️⃣  CONTENIDO
========================================================== */
switch ($page) {

    case 'login':
        include 'app/views/auth/login.php';
        break;

    case 'productos':

    role_required(['Administrador']);

    require_once 'app/controllers/ProductController.php';
    $controller = new ProductController();

    $action = $_GET['action'] ?? 'index';

    switch ($action) {

        case 'create':
            $controller->create();
            break;

        case 'edit':
            $controller->edit();
            break;

        case 'destroy':
            $controller->destroy();
            break;

        default:
            $controller->index();
            break;
    }

    break;

    case 'clientes':

    role_required(['Administrador']);

    require_once 'app/controllers/ClienteController.php';
    $controller = new ClienteController();

    $action = $_GET['action'] ?? 'index';

    switch ($action) {

        case 'create':
            $controller->create();
            break;

        case 'edit':
            $controller->edit();
            break;

        case 'store':
            $controller->store();
            break;

        case 'update':
            $controller->update();
            break;

        case 'destroy':
            $controller->destroy();
            break;

        default:
            $controller->index();
            break;
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

        case 'store':
            $controller->store();
            break;

        case 'show':
            $controller->show();
            break;

        case 'anular':
            $controller->anular();
            break;

        default:
            $controller->index();
            break;
    }

    break;

    case 'ventas':

    require_once 'app/controllers/VentaController.php';

    $controller = new VentaController();
    $action = $_GET['action'] ?? 'index';

    switch ($action) {

        case 'create':
            $controller->create();
            break;

        case 'store':
            $controller->store();
            break;

        case 'show':
            $controller->show();
            break;

        case 'anular':
            $controller->anular();
            break;

        case 'pdf':
            $controller->pdf();
            break;

        default:
            $controller->index();
            break;
    }

    break;

    case 'factura':

    require_once 'app/controllers/FacturaController.php';

    $controller = new FacturaController();
    $action = $_GET['action'] ?? 'index';

    switch ($action) {

        case 'calcular':
            $controller->calcular();
            break;

        case 'resultado':
            $controller->resultado();
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

    if ($action === 'grafica') {
        $controller->grafica();
    } else {
        include 'app/views/reportes/index.php';
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

    case 'home':
        include 'app/views/home/index.php';
        break;

    default:
        include 'app/views/dashboard/index.php';
}

/* ==========================================================
   8️⃣  FOOTER
========================================================== */
include 'app/views/layouts/footer.php';
ob_end_flush();
?>