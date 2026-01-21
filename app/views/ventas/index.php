<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Listado de Ventas</h4>
    <a href="index.php?page=ventas&action=create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Venta
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Subtotal</th>
                    <th>IVA</th>
                    <th>Total</th>
                    <th width="120">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($ventas as $v): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $v['fech_venta'] ?></td>
                        <td><?= $v['clie_venta'] ?></td>
                        <td><?= $v['nomb_usuario'] ?></td>
                        <td class="text-end">$<?= number_format($v['subt_venta'], 2) ?></td>
                        <td class="text-end">$<?= number_format($v['iva_venta'], 2) ?></td>
                        <td class="text-end fw-bold">$<?= number_format($v['tota_venta'], 2) ?></td>
                        <td class="text-center">
                            <a href="index.php?page=ventas&action=show&id=<?= $v['id_venta'] ?>"
                                class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="index.php?page=ventas&action=anular&id=<?= $v['id_venta'] ?>"
                                onclick="return confirm('¿Eliminar Venta?')"
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

                <!-- Botón ANTERIOR -->
                <?php $pagina = $_GET['p'] ?? 1; ?>
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="index.php?page=ventas&p=<?= max(1, $pagina - 1) ?>">
                        &laquo; Anterior
                    </a>
                </li>

                <?php
                // Paginación limitada a 7 páginas visibles
                $maxVisible = 7;
                $start = max(1, $pagina - 3);
                $end = min($paginas, $start + $maxVisible - 1);

                // Ajustar start si estamos al final
                if ($end - $start + 1 < $maxVisible) {
                    $start = max(1, $end - $maxVisible + 1);
                }

                for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                        <a class="page-link" href="index.php?page=ventas&p=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Botón SIGUIENTE -->
                <li class="page-item <?= ($pagina >= $paginas) ? 'disabled' : '' ?>">
                    <a class="page-link" href="index.php?page=ventas&p=<?= min($paginas, $pagina + 1) ?>">
                        Siguiente &raquo;
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</div>