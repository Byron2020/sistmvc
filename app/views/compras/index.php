<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">
        <i class="bi bi-cart-check"></i> Compras
    </h3>

    <a href="index.php?page=compras&action=create"
        class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>Factura</th>
                    <th>Fecha</th>
                    <th>Subtotal</th>
                    <th>IVA</th>
                    <th>Total</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($compras as $c): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $c['nume_factura'] ?></td>
                        <td><?= $c['fech_factura'] ?></td>
                        <td class="text-end">$<?= number_format($c['subt_compra'], 2) ?></td>
                        <td class="text-end">$<?= number_format($c['iva_compra'], 2) ?></td>
                        <td class="text-end"><strong>$<?= number_format($c['tota_compra'], 2) ?></strong></td>
                        <td><?= $c['usuario'] ?></td>
                        <td class="text-center">
                            <a href="index.php?page=compras&action=show&id=<?= $c['id_compra'] ?>"
                                class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="index.php?page=compras&action=anular&id=<?= $c['id_compra'] ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Anular esta compra?')">
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
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="index.php?page=compras&p=<?= max(1, $pagina - 1) ?>">
                        &laquo; Anterior
                    </a>
                </li>

                <?php
                // Paginación limitada a 7 páginas visibles
                $maxVisible = 7;
                $start = max(1, $pagina - 3);
                $end = min($totalPaginas, $start + $maxVisible - 1);

                // Ajustar start si estamos al final
                if ($end - $start + 1 < $maxVisible) {
                    $start = max(1, $end - $maxVisible + 1);
                }

                for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                        <a class="page-link" href="index.php?page=compras&p=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Botón SIGUIENTE -->
                <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                    <a class="page-link" href="index.php?page=compras&p=<?= min($totalPaginas, $pagina + 1) ?>">
                        Siguiente &raquo;
                    </a>
                </li>

            </ul>
        </nav>


    </div>
</div>