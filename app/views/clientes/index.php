<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">
        <i class="bi bi-people fs-5"></i> Clientes
    </h3>

    <a href="index.php?page=clientes&action=create"
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
                <th>Apellido</th>
                <th>Cédula</th>
                <th>Celular</th>
                <th>Ciudad</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th width="120">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientes)): ?>
                <?php $i = 1; foreach ($clientes as $c): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($c['nomb_cliente']) ?></td>
                        <td><?= htmlspecialchars($c['apel_cliente']) ?></td>
                        <td><?= htmlspecialchars($c['cedu_cliente']) ?></td>
                        <td><?= htmlspecialchars($c['celu_cliente']) ?></td>
                        <td><?= htmlspecialchars($c['ciud_cliente']) ?></td>
                        <td><?= htmlspecialchars($c['dire_cliente']) ?></td>
                        <td class="text-center">
                            <?php if ($c['esta_cliente'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="index.php?page=clientes&action=edit&id=<?= $c['id_cliente'] ?>"
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="index.php?page=clientes&action=destroy&id=<?= $c['id_cliente'] ?>"
                               onclick="return confirm('¿Eliminar cliente?')"
                               class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        No existen clientes registrados
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINACIÓN -->
    <nav>
        <ul class="pagination justify-content-end">

            <!-- ANTERIOR -->
            <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="index.php?page=clientes&p=<?= $pagina - 1 ?>">
                    &laquo;
                </a>
            </li>

            <!-- NÚMEROS -->
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                    <a class="page-link"
                       href="index.php?page=clientes&p=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- SIGUIENTE -->
            <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="index.php?page=clientes&p=<?= $pagina + 1 ?>">
                    &raquo;
                </a>
            </li>

        </ul>
    </nav>
</div>
