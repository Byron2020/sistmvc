<?php

require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Product.php';
// PDF-> Mpdf
require_once __DIR__ . '/../../vendor/autoload.php';

use Mpdf\Mpdf;
//------------------

class VentaController
{
    /* =========================================
       LISTADO CON PAGINACIÓN
    ========================================= */

    public function index()
    {
        role_required(['Administrador', 'Vendedor']);

        $venta = new Venta();

        $pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $limite = 10;
        $offset = ($pagina - 1) * $limite;

        $ventas   = $venta->getPaginadas($limite, $offset);
        $total    = $venta->totalRegistros();
        $paginas  = ceil($total / $limite);

        include 'app/views/ventas/index.php';
    }

    /* =========================================
       FORMULARIO NUEVA VENTA
    ========================================= */
    public function create()
    {

        role_required(['Administrador', 'Vendedor']);

        $productModel = new Product();
        $productos = $productModel->getAll()->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/ventas/create.php';
    }

    /* =========================================
       GUARDAR VENTA (POST)
    ========================================= */
    public function store()
    {
        role_required(['Administrador', 'Vendedor']);

        if (
            empty($_POST['carrito']) ||
            empty($_POST['subtotal']) ||
            empty($_POST['total'])
        ) {
            die('Datos incompletos');
        }

        $carrito = json_decode($_POST['carrito'], true);

        if (!$carrito || count($carrito) === 0) {
            die('Carrito vacío');
        }

        $data = [
            'usuario'  => $_SESSION['user_id'],
            'cliente'  => $_POST['clie_venta'],
            'fecha'    => $_POST['fech_venta'],
            'subtotal' => $_POST['subtotal'],
            'iva'      => $_POST['iva_monto'],
            'total'    => $_POST['total'],
            'carrito'  => $carrito
        ];

        try {
            $venta = new Venta();
            $venta->guardarVenta($data);

            $_SESSION['swal'] = [
                'icon'  => 'success',
                'title' => 'Venta registrada',
                'text'  => 'La venta se guardó correctamente',
                'timer' => 3000
            ];

            header('Location: index.php?page=ventas');
            exit;
        } catch (Exception $e) {

            $_SESSION['swal'] = [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $e->getMessage()
            ];

            header('Location: index.php?page=ventas');
            exit;
        }
    }

    /* =========================================
       VER DETALLE
    ========================================= */
    public function show()
    {
        role_required(['Administrador', 'Vendedor']);

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=ventas');
            exit;
        }

        $venta = new Venta();
        $ventaData = $venta->getById($_GET['id']);

        $detalle = $venta->getDetalle($_GET['id']);
        include 'app/views/ventas/show.php';
    }

    /* =========================================
       ANULAR VENTA
    ========================================= */
    public function anular()
    {
        role_required(['Administrador', 'Vendedor']);

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=ventas');
            exit;
        }

        try {
            $venta = new Venta();
            $venta->anular($_GET['id']);

            $_SESSION['swal'] = [
                'icon'  => 'success',
                'title' => 'Venta anulada',
                'text'  => 'La venta fue anulada correctamente',
                'timer' => 3000
            ];
        } catch (Exception $e) {

            $_SESSION['swal'] = [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $e->getMessage()
            ];
        }

        header('Location: index.php?page=ventas');
        exit;
    }
    // Export PDF
    public function pdf()
    {
        role_required(['Administrador', 'Vendedor']);

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=ventas');
            exit;
        }

        $ventaModel = new Venta();
        $ventaData  = $ventaModel->getById($_GET['id']);
        $detalle    = $ventaModel->getDetalle($_GET['id']);

        if (!$ventaData) {
            die('Venta no encontrada');
        }

        // Ruta ABSOLUTA del logo (IMPORTANTE)
        $logo = __DIR__ . '/../../app/img/milogo.jpeg';

        // Crear mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 35,
            'margin_bottom' => 25,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);

        // Encabezado
        $header = '
        <table width="100%">
            <tr>
                <td width="40%" style="border:none;">
                    <img src="' . $logo . '" width="90">
                </td>
                <td width="60%" style="border: none; text-align:right; font-size:11px;">
                    <strong>SPF DISTRIBUIDORA</strong><br>
                    RUC: 0202169371001<br>
                    Guaranda - Ecuador<br>
                    Tel: 0981394965<br>
                    gamesyar2001@gmail.com
                </td>
            </tr>
        </table>
        ';

        // Pie de página
        $footer = '
        <hr>
        <table width="100%" style="border:none;">
                <tr>
                    <td align="left" style="border:none; font-size:9px;">Página {PAGENO} / {nbpg}</td>
                    <td align="right" style="border:none; font-size:9px;">' . date('d/m/Y') . '</td>
                </tr>
            </table>
        ';

        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);

        // Cargar vista HTML
        ob_start();
        include 'app/views/ventas/pdf.php';
        $html = ob_get_clean();

        $mpdf->WriteHTML($html);

        // Mostrar PDF en navegador
        $fechaHora = date('d-m-Y'); // Ej: 2026-01-10
        $nombreArchivo = 'Venta_SPF' . $ventaData['id_venta'] . '_' . $fechaHora . '.pdf';

        $mpdf->Output($nombreArchivo, 'D'); // D = download
        exit;
    }
}
