<h4>Detalle de Compra</h4>

<p><strong>Factura:</strong> <?= $compra['nume_factura'] ?></p>
<p><strong>Fecha:</strong> <?= $compra['fech_factura'] ?></p>
<p><strong>Usuario:</strong> <?= $compra['nomb_usuario'] ?></p>

<table class="table table-sm table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Num</th>
            <th>Producto</th>
            <th class="text-end">Precio</th>
            <th class="text-end">Cantidad</th>
            <th class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; foreach ($detalle as $d): ?>
            <tr>
                <td><?=  $i++; ?></td>
                <td><?= $d['nomb_producto'] ?></td>
                <td class="text-end">$<?= number_format($d['prec_cdetalle'], 2) ?></td>
                <td class="text-end"><?= $d['cant_cdetalle'] ?></td>
                <td class="text-end">$<?= number_format($d['tota_cdetalle'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="row">
    <div class="col-md-4 text-end">
        <strong>Subtotal:</strong> $<?= number_format($compra['subt_compra'], 2) ?>
    </div>
    <div class="col-md-4 text-end">
        <strong>IVA:</strong> $<?= number_format($compra['iva_compra'], 2) ?>
    </div>
    <div class="col-md-4 text-end">
        <strong>Total:</strong> $<?= number_format($compra['tota_compra'], 2) ?>
    </div>
</div>