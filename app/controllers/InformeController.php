<?php

use Mpdf\Mpdf;

require_once 'app/models/Informe.php';

class InformeController
{
    /**
     * MENÚ PRINCIPAL DE INFORMES
     */
    public function index()
    {
        require 'app/views/informes/index.php';
    }

    public function iproductos()
    {
        $model = new Informe();

        $pagina = $_GET['p'] ?? 1;
        $limite = 10;
        $offset = ($pagina - 1) * $limite;

        $productos = $model->productosPaginados($limite, $offset);
        $total     = $model->totalProductos();
        $paginas   = ceil($total / $limite);

        $_SESSION['pdf_data'] = [
            'titulo' => 'Inventario de Productos',
            'tipo'   => 'productos'
        ];

        require 'app/views/informes/iproductos.php';
    }
    public function icompras()
    {
        $model = new Informe();

        // fechas (por defecto último mes)
        $fechaFin = $_GET['ff'] ?? date('Y-m-d');
        $fechaIni = $_GET['fi'] ?? date('Y-m-d', strtotime('-1 month'));

        // paginación
        $pagina = $_GET['p'] ?? 1;
        $limite = 10;
        $offset = ($pagina - 1) * $limite;

        $compras = $model->comprasPaginadas($fechaIni, $fechaFin, $limite, $offset);
        $total   = $model->totalCompras($fechaIni, $fechaFin);
        $paginas = ceil($total / $limite);

        // guardar para PDF
        $_SESSION['reporte_pdf'] = [
            'tipo'   => 'compras',
            'titulo' => 'Reporte de Compras',
            'data'   => $compras
        ];

        require 'app/views/informes/icompras.php';
    }

    /* =====================================
       INFORME DE VENTAS
    ===================================== */
    public function iventas()
    {
        $model = new Informe();

        // Fechas (GET o por defecto)
        $fechaInicio = $_GET['fi'] ?? date('Y-m-d', strtotime('-1 month'));
        $fechaFin    = $_GET['ff'] ?? date('Y-m-d');

        // Paginación
        $pagina = $_GET['p'] ?? 1;
        $limite = 10; // ventas por página
        $offset = ($pagina - 1) * $limite;

        // Datos paginados
        $ventas = $model->ventasPorFechaPaginadas($fechaInicio, $fechaFin, $limite, $offset);

        // Total de ventas en rango
        $totalVentas = $model->totalVentas($fechaInicio, $fechaFin);
        $paginas = ceil($totalVentas / $limite);

        // Guardar para PDF
        $_SESSION['reporte_pdf'] = [
            'tipo'   => 'ventas',
            'titulo' => 'Informe de Ventas',
            'data'   => $ventas,
            'fi'     => $fechaInicio,
            'ff'     => $fechaFin
        ];

        require 'app/views/informes/iventas.php';
    }


    /**
     * EXPORTAR PDF (SIN PAGINACIÓN)
     */
    public function ipdf()
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        require_once __DIR__ . '/../models/Informe.php';

        $tipo = $_GET['tipo'] ?? '';
        $fi   = $_GET['fi'] ?? date('Y-m-d', strtotime('-1 month'));
        $ff   = $_GET['ff'] ?? date('Y-m-d');

        $model = new Informe();

        $titulo = '';
        $data   = [];

        switch ($tipo) {

            case 'productos':
                $titulo = 'Informe de Productos';
                $data = $model->productosTodos();
                break;

            case 'compras':
                $titulo = "Informe de Compras ($fi a $ff)";
                $data = $model->comprasPorFecha($fi, $ff);
                break;

            case 'ventas':
                $titulo = "Informe de Ventas ($fi a $ff)";
                $data = $model->ventasPorFecha($fi, $ff);
                break;

            default:
                die('Tipo de informe no válido');
        }

        // LOGO
        $logo = __DIR__ . '/../../app/img/milogo.jpeg';

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'orientation' => 'L',
            'margin_top' => 35,
            'margin_bottom' => 20
        ]);

        /* =========================
       HEADER
    ========================== */
        $mpdf->SetHTMLHeader("
        <table width='100%'>
            <tr>
                <td width='40%'>
                    <img src='$logo' width='90'>
                </td>
                <td width='60%' style='text-align:right; font-size:11px;'>
                    <strong>SPF DISTRIBUIDORA</strong><br>
                    RUC: 0202169371001<br>
                    Guaranda - Ecuador<br>
                    Tel: 0981394965<br>
                    gamesyar2001@gmail.com
                </td>
            </tr>
        </table>
    ");
        $css = "
    <style>
    .totales-wrapper {
        width: 100%;
        margin-top: 15px;
    }
    .totales {
        width: 35%;
        margin-left: auto;
        border-collapse: collapse;
    }
    .totales th,
    .totales td {
        border: 1px solid #000;
        padding: 3px;
    }
    .totales th {
        background-color: #f2f2f2;
        text-align: left;
    }
    .totales .total {
        font-weight: bold;
    }
    </style>
    ";
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

        /* =========================
       CONTENIDO
    ========================== */
        $html = "<h3 style='text-align:center'>$titulo</h3>";
        $html .= "<table border='1' width='100%' style='border-collapse:collapse'>
                <tr style='background:#f2f2f2'>
                    <th>#</th>";

        if (!empty($data)) {
            foreach (array_keys($data[0]) as $col) {
                $html .= "<th>" . ucfirst(str_replace('_', ' ', $col)) . "</th>";
            }
            $html .= "</tr>";

            $i = 1;
            $sumSubtotal = $sumIva = $sumTotal = 0;

            foreach ($data as $row) {
                $html .= "<tr><td align='center'>" . $i . "</td>";
                $i++;

                foreach ($row as $key => $val) {

                    // FECHAS
                    if (str_contains($key, 'fech')) {
                        $html .= "<td>$val</td>";
                    }

                    // PRECIOS / SUBTOTALES
                    elseif (
                        $key === 'precvent_producto' ||
                        str_contains($key, 'subt')
                    ) {
                        $sumSubtotal += is_numeric($val) ? (float)$val : 0;
                        $html .= "<td align='right'>$ " . number_format($val, 2) . "</td>";
                    }

                    // IVA
                    elseif (str_contains($key, 'iva')) {
                        $sumIva += (float)$val;
                        $html .= "<td align='right'>$ " . number_format($val, 2) . "</td>";
                    }

                    // TOTALES
                    elseif (
                        str_contains($key, 'total') ||
                        str_contains($key, 'tota')
                    ) {
                        $sumTotal += (float)$val;
                        $html .= "<td align='right'>$ " . number_format($val, 2) . "</td>";
                    }

                    // NUMÉRICOS (stock, ids, cantidades)
                    elseif (is_numeric($val)) {
                        $html .= "<td align='right'>" . number_format($val) . "</td>";
                    }

                    // TEXTO
                    else {
                        $html .= "<td>" . htmlspecialchars($val) . "</td>";
                    }
                }

                $html .= "</tr>";
            }
        } else {
            $html .= "<tr><td colspan='10' align='center'>No hay datos</td></tr>";
        }

        $html .= "</table>";
        /* =========================
           TOTALES
            ========================== */

        if (in_array($tipo, ['compras', 'ventas'])) {
            $html .= '
                <br>
                <div class="totales-wrapper">
                    <table class="totales">
                        <tr>
                            <th>Subtotal</th>
                            <td style="text-align:right;">$ ' . number_format($sumSubtotal, 2) . '</td>
                        </tr>
                        <tr>
                            <th>IVA</th>
                            <td style="text-align:right;">$ ' . number_format($sumIva, 2) . '</td>
                        </tr>
                        <tr class="total">
                            <th>Total</th>
                            <td style="text-align:right;"><strong>$ ' . number_format($sumTotal, 2) . '</strong></td>
                        </tr>
                    </table>
                </div>
                ';
        }

        /* =========================
       FOOTER
    ========================== */
        $mpdf->SetHTMLFooter("
        <table width='100%' style='font-size:10px'>
            <tr>
                <td>Página {PAGENO} / {nbpg}</td>
                <td align='right'>" . date('d/m/Y') . "</td>
            </tr>
        </table>
    ");

        $mpdf->WriteHTML($html);


        // Mostrar PDF en navegador
        $fechaHora = date('d-m-Y'); // Ej: 2026-01-10
        $nombreArchivo = 'InformeSPF_' . $tipo . '_' . $fechaHora . '.pdf';
        $mpdf->Output($nombreArchivo, 'I'); //Nueva pestaña

    }
}
