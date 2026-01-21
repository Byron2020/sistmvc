<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">
        <i class="bi-box-seam fs-5"></i></i> Productos
    </h3>

    <a href="index.php?page=productos&action=create"
        class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo
    </a>
</div>

<div class="container-fluid">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio Venta</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; foreach ($products as $p): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $p['nomb_producto'] ?></td>
                    <td><?= $p['desc_producto'] ?></td>
                    <td><?= $p['precvent_producto'] ?></td>
                    <td><?= $p['stoc_producto'] ?></td>
                    <td>
                        <a href="index.php?page=productos&action=edit&id=<?= $p['id_producto'] ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a href="index.php?page=productos&action=delete&id=<?= $p['id_producto'] ?>"
                            onclick="return confirm('¿Eliminar producto?')"
                            class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <nav>
        <ul class="pagination justify-content-end">

            <!-- ANTERIOR -->
            <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                <a class="page-link"
                    href="index.php?page=productos&p=<?= $pagina - 1 ?>">
                    &laquo;
                </a>
            </li>

            <!-- NÚMEROS -->
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                    <a class="page-link"
                        href="index.php?page=productos&p=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- SIGUIENTE -->
            <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link"
                    href="index.php?page=productos&p=<?= $pagina + 1 ?>">
                    &raquo;
                </a>
            </li>

        </ul>
    </nav>

</div>