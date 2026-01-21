<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <!-- IZQUIERDA: PDF -->
        <a href="index.php?page=ventas&action=pdf&id=<?= $ventaData['id_venta'] ?>"
            class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Generar PDF
        </a>
        <!-- DERECHA: VOLVER -->
        <a href="index.php?page=ventas"
            class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <h4 class="text-center">Detalle de Venta</h4>

    <!-- CABECERA -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Fecha:</strong> <?= $ventaData['fech_venta'] ?>
                </div>
                <div class="col-md-4">
                    <strong>Cliente:</strong> <?= $ventaData['clie_venta'] ?>
                </div>
                <div class="col-md-4">
                    <strong>Usuario:</strong> <?= $ventaData['nomb_usuario'] ?>
                </div>
            </div>
        </div>
    </div>

    <!-- DETALLE -->
    <table class="table table-bordered table-sm">
        <thead class="table-dark">
            <tr >
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; foreach ($detalle as $d): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $d['nomb_producto'] ?></td>
                    <td class="text-end"><?= $d['cant_vdetalle'] ?></td>
                    <td class="text-end">$<?= number_format($d['prec_vdetalle'], 2) ?></td>
                    <td class="text-end">$<?= number_format($d['tota_vdetalle'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-4 text-end">
            <strong>Subtotal:</strong> $<?= number_format($ventaData['subt_venta'], 2) ?>
        </div>
        <div class="col-md-4 text-end">
            <strong>IVA:</strong> $<?= number_format($ventaData['iva_venta'], 2) ?>
        </div>
        <div class="col-md-4 text-end">
            <strong>Total:</strong> $<?= number_format($ventaData['tota_venta'], 2) ?>
        </div>
    </div>
</div>