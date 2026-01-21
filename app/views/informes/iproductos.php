<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <a href="index.php?page=informes&action=pdf&tipo=productos"
           class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </a>

        <a href="index.php?page=informes"
           class="btn btn-secondary">
            Volver
        </a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Descripción</th>
                <th class="text-end">Precio</th>
                <th class="text-end">Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1 + (($pagina - 1) * 10); ?>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($p['nomb_producto']) ?></td>
                    <td><?= htmlspecialchars($p['desc_producto']) ?></td>
                    <td class="text-end">$ <?= number_format($p['precvent_producto'], 2) ?></td>
                    <td class="text-end"><?= number_format($p['stoc_producto']) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- PAGINACIÓN -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $paginas; $i++): ?>
                <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                    <a class="page-link"
                       href="index.php?page=informes&action=iproductos&p=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor ?>
        </ul>
    </nav>

</div>
