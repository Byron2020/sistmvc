<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
        }

        .info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        td.left {
            text-align: left;
        }

        .totales {
            margin-top: 10px;
            width: 40%;
            float: right;
            margin-left: auto;
        }

        .tabla-header {
            border: none;
        }
    </style>
</head>

<body>

    <h2>COMPROBANTE DE VENTA</h2>

    <div class="info">
        <p><strong>Fecha:</strong> <?= $ventaData['fech_venta'] ?></p>
        <p><strong>Cliente:</strong> <?= $ventaData['clie_venta'] ?></p>
        <p><strong>Vendedor:</strong> <?= $ventaData['nomb_usuario'] ?></p>
    </div>

    <table width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cant.</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($detalle as $d): ?>
                <tr>
                    <td style="text-align:center;"><?= $i++ ?></td>
                    <td class="left"><?= $d['nomb_producto'] ?></td>
                    <td style="text-align:center;"><?= $d['cant_vdetalle'] ?></td>
                    <td style="text-align:right;"><?= number_format($d['prec_vdetalle'], 2) ?></td>
                    <td style="text-align:right;"><?= number_format($d['tota_vdetalle'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="totales">
        <tr>
            <th>Subtotal</th>
            <td style="text-align:right;"><?= number_format($ventaData['subt_venta'], 2) ?></td>
        </tr>
        <tr>
            <th>IVA</th>
            <td style="text-align:right;"><?= number_format($ventaData['iva_venta'], 2) ?></td>
        </tr>
        <tr>
            <th>Total</th>
            <td style="text-align:right;"><strong><?= number_format($ventaData['tota_venta'], 2) ?></strong></td>
        </tr>
    </table>

</body>

</html>