<?php

use Mpdf\Mpdf;

require_once 'app/models/ReporteModel.php';

class ReporteController
{
    public function index()
    {
        require 'app/views/reportes/index.php';
    }

    public function grafica()
    {
        $model = new ReporteModel();
        $tipo = $_GET['tipo'] ?? '';
        $dataGrafico = [];
        $dataTabla   = [];
        switch ($tipo) {
            case 'ventas':
                $titulo = 'Ventas mensuales ($)';
                $dataGrafico = $model->ventasMensuales();
                $dataTabla   = $dataGrafico;
                break;

            case 'productos':
                $titulo = 'Productos más vendidos';
                $dataGrafico = $model->productosMasVendidos();
                $dataTabla   = $dataGrafico;
                break;
            case 'comparativo':
                $titulo = 'Compras vs Ventas (Mes actual)';
                $dataGrafico = $model->comprasVsVentasMesActual();
                $dataTabla   = $dataGrafico;
                break;
            case 'barras':
                $titulo = 'Total Compras vs Total Ventas';

                $totales = $model->totalComprasVsVentas();

                //gráfico
                $dataGrafico = $totales;

                //tabla
                $dataTabla = [[
                    'compras'    => $totales['total_compras'],
                    'ventas'     => $totales['total_ventas'],
                    'diferencia' => $totales['total_compras'] - $totales['total_ventas']
                ]];
                break;

            default:
                $titulo = 'Reporte';
                $data = [];
                $titulo = 'Reporte';
                $dataGrafico = [];
                $dataTabla   = [];
        }

        //GUARDAR DATOS PARA EL PDF
        $_SESSION['reporte_data'] = $dataTabla;

        require 'app/views/reportes/grafica.php';
    }

    public function pdf()
    {
        if (empty($_POST['imagen'])) {
            die('No se recibió el gráfico');
        }


        // Ruta ABSOLUTA del logo (IMPORTANTE)
        $logo = __DIR__ . '/../../app/img/milogo.jpeg';

        $imgBase64 = $_POST['imagen'];
        $titulo = $_POST['titulo'] ?? 'Reporte';
        $data   = $_SESSION['reporte_data'] ?? [];

        require_once __DIR__ . '/../../vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'orientation' => 'L',
            'margin_top' => 35,// AUMENTAR ESTE VALOR
            'margin_bottom' => 20,

            //PERMITIR BASE64
            'allow_url_fopen' => true,
            'showImageErrors' => true,
            'img_dpi' => 96
        ]);

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
        $mpdf->SetHTMLHeader($header);

        $html = "
        <h2 style='text-align:center;'>$titulo</h2>
                    
        <div style='text-align:center'>
            <img src='$imgBase64' style='width:100%;'>
        </div>
        <hr>
        <hr>        
        <hr>
        <h4>Datos del gráfico</h4>
        <table border='1' width='100%' style='border-collapse: collapse;'>
        ";

        if (!empty($data)) {

            // ENCABEZADOS
            $html .= '<tr style="background:#f2f2f2">';
            $html .= '<th width="5%">#</th>'; // 👈 columna #

            foreach (array_keys($data[0]) as $col) {
                $html .= '<th>' . ucfirst(str_replace('_', ' ', $col)) . '</th>';
            }
            $html .= '</tr>';

            // FILAS
            $i = 1;
            foreach ($data as $row) {
                $html .= '<tr>';
                $html .= '<td align="center">' . $i++ . '</td>'; // 👈 contador
                foreach ($row as $key => $val) {

                    // CAMPOS DE TEXTO
                    if (in_array($key, ['fecha', 'nomb_producto', 'mes'])) {
                        $html .= '<td>' . htmlspecialchars($val) . '</td>';
                    } // CAMPOS NUMÉRICOS SIN $
                    elseif (in_array($key, ['cantidad', 'stoc_producto'])) {
                        $html .= '<td align="right">' . number_format((float)$val) . '</td>';
                    } // CAMPOS MONETARIOS $
                    else {
                        $html .= '<td align="right">$ ' . number_format((float)$val, 2) . '</td>';
                    }
                }

                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="10" align="center">No hay datos</td></tr>';
        }

        $html .= '</table>';

        $mpdf->SetHTMLFooter('
            <table width="100%" style="border:none; font-size:10px;" >
                <tr>
                    <td align="left">Página {PAGENO} / {nbpg}</td>
                    <td align="right">' . date('d/m/Y') . '</td>
                </tr>
            </table>
        ');

        $mpdf->WriteHTML($html);
        $mpdf->Output('reporte.pdf', 'I');
        // LIMPIAR
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
}
