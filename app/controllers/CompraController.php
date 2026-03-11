<?php
require_once 'app/models/Compra.php';
require_once 'app/models/Product.php';
class CompraController
{
    public function index()
    {

        $model = new Compra();

        $pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $limite = 10;
        $offset = ($pagina - 1) * $limite;

        $compras = $model->getPaginated($limite, $offset);
        $total = $model->countAll();
        $totalPaginas = ceil($total / $limite);

        include 'app/views/compras/index.php';
    }

    // 👉 SOLO MUESTRA LA VISTA
    public function create()
    {
        $productModel = new Product();
        $productos = $productModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/compras/create_compras.php';
    }

    // 👉 SOLO GUARDA (POST)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=compras');
            exit;
        }

        if (
            empty($_POST['nume_factura']) ||
            empty($_POST['fech_factura']) ||
            empty($_POST['carrito'])
        ) {
            die('Datos incompletos');
        }

        $carrito = json_decode($_POST['carrito'], true);

        if (!is_array($carrito) || count($carrito) === 0) {
            die('Carrito inválido');
        }

        $dataCompra = [
            'usuario'      => $_SESSION['user_id'],
            'nume_factura' => $_POST['nume_factura'],
            'fech_factura' => $_POST['fech_factura'],
            'subt_compra'  => $_POST['subtotal'],
            'iva_compra'   => $_POST['iva_monto'], // ✅ IVA REAL
            'tota_compra'  => $_POST['total'],
            'carrito'      => $carrito
        ];

        $compra = new Compra();
        $compra->guardarCompra($dataCompra);
        $_SESSION['swal'] = [
            'icon' => 'success',
            'title' => 'Operación exitosa',
            'text' => 'Datos guardados correctamente',
            'timer' => 3500
        ];
        header('Location: index.php?page=compras');
        exit;
    }

    // SHOW DETALLES Y ELIMINAR
    public function show()
    {
        $id = $_GET['id'];

        $model = new Compra();
        $compra = $model->getById($id);
        $detalle = $model->getDetalle($id);

        include 'app/views/compras/show.php';
    }

    public function anular()
    {
        $id = $_GET['id'];

        $model = new Compra();
        $model->anularCompra($id);
        $_SESSION['swal'] = [
            'icon' => 'success',
            'title' => 'Operación exitosa',
            'text' => 'Datos eliminados correctamente',
            'timer' => 3500
        ];
        header('Location: index.php?page=compras');
        exit;
    }
}
