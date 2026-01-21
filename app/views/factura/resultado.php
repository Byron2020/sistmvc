<div class="container mt-4">

    <h3 class="mb-4">Resultado del cálculo</h3>

    <div class="row mb-3">
        <div class="col-md-4">
            <strong>Subtotal:</strong><br>
            <?= number_format($data['subtotal'], 5) ?>
        </div>
        <div class="col-md-4">
            <strong>IVA total:</strong><br>
            <?= number_format($data['iva_total'], 5) ?>
        </div>
        <div class="col-md-4">
            <strong>Total factura:</strong><br>
            <?= number_format($data['total'], 5) ?>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr class="text-center">
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>IVA prorrateado</th>
                <th>Costo unitario final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['detalle'] as $i => $r): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($r['producto']) ?></td>
                    <td class="text-end"><?= number_format($r['cantidad'], 0) ?></td>
                    <td class="text-end"><?= number_format($r['iva_producto'], 5) ?></td>
                    <td class="text-end">$<strong><?=number_format($r['costo_unitario'], 5) ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php?page=factura" class="btn btn-secondary">
        ⬅ Nuevo cálculo
    </a>

</div>
