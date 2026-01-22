<?php
// ===============================
// FECHAS POR DEFECTO
// ===============================
$fechaFinDefault    = date('Y-m-d');
$fechaInicioDefault = date('Y-m-d', strtotime('-1 month'));
?>

<div class="container mt-4">

    <!-- =========================
         BOTONES SUPERIORES
    ========================== -->
    <div class="d-flex justify-content-between mb-3">
        <a href="index.php?page=informes&action=pdf&tipo=ventas"
            class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </a>

        <a href="index.php?page=informes"
            class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <!-- =========================
         FILTRO DE FECHAS
    ========================== -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                <input type="hidden" name="page" value="informes">
                <input type="hidden" name="action" value="iventas">

                <div class="col-md-4">
                    <label class="form-label">Fecha inicial</label>
                    <input type="date"
                        name="fi"
                        class="form-control"
                        value="<?= $_GET['fi'] ?? $fechaInicioDefault ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha final</label>
                    <input type="date"
                        name="ff"
                        class="form-control"
                        value="<?= $_GET['ff'] ?? $fechaFinDefault ?>">
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Obtener datos
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- =========================
         LISTADO DE VENTAS
    ========================== -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Listado de Ventas</h5>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Fecha Venta</th>
                        <th>Cliente</th>
                        <th>Usuario</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">Descuento</th>
                        <th class="text-end">Iva</th>
                        <th class="text-end">Total</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (!empty($ventas)): ?>
                        <?php
                        $i = 1;
                        $sumSubtotal = 0;
                        $sumDescuento      = 0;
                        $sumIva      = 0;
                        $sumTotal    = 0;
                        ?>
                        <?php foreach ($ventas as $v): ?>
                            <?php
                            $sumSubtotal += $v['subt_venta'];
                            $sumIva      += $v['iva_venta'];
                            $sumDescuento      += $v['desc_venta'];
                            $sumTotal    += $v['tota_venta'];
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($v['fech_venta']) ?></td>
                                <td><?= htmlspecialchars($v['nomb_cliente'].' '.$v['apel_cliente']) ?></td>
                                <td><?= htmlspecialchars($v['nomb_usuario']) ?></td>
                                <td class="text-end">$ <?= number_format($v['subt_venta'], 2) ?></td>
                                <td class="text-end">$ <?= number_format($v['desc_venta'], 2) ?></td>
                                <td class="text-end">$ <?= number_format($v['iva_venta'], 2) ?></td>
                                <td class="text-end">$ <?= number_format($v['tota_venta'], 2) ?></td>
                                <td><?= htmlspecialchars($v['regi_venta']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                No hay ventas en el rango seleccionado
                            </td>
                        </tr>
                    <?php endif ?>

                </tbody>
            </table>
            <?php if ($paginas > 1): ?>
                <nav aria-label="Paginación">
                    <ul class="pagination justify-content-center mt-3">

                        <!-- Botón ANTERIOR -->
                        <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=informes&action=iventas&fi=<?= $fechaInicio ?>&ff=<?= $fechaFin ?>&p=<?= max(1, $pagina - 1) ?>">
                                &laquo; Anterior
                            </a>
                        </li>

                        <?php
                        // Máximo 7 páginas visibles
                        $maxVisible = 7;
                        $start = max(1, $pagina - 3);
                        $end = min($paginas, $start + $maxVisible - 1);

                        if ($end - $start + 1 < $maxVisible) {
                            $start = max(1, $end - $maxVisible + 1);
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=informes&action=iventas&fi=<?= $fechaInicio ?>&ff=<?= $fechaFin ?>&p=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Botón SIGUIENTE -->
                        <li class="page-item <?= ($pagina >= $paginas) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=informes&action=iventas&fi=<?= $fechaInicio ?>&ff=<?= $fechaFin ?>&p=<?= min($paginas, $pagina + 1) ?>">
                                Siguiente &raquo;
                            </a>
                        </li>

                    </ul>
                </nav>
            <?php endif; ?>

            <!-- =========================
                 TOTALES
            ========================== -->
            <?php if (!empty($ventas)): ?>
                <div class="row justify-content-end mt-3">
                    <div class="col-md-4">
                        <table class="table table-bordered">
                            <tr>
                                <th>Subtotal</th>
                                <td class="text-end">$ <?= number_format($sumSubtotal, 2) ?></td>
                            </tr>
                            <tr>
                                <th>Descuento</th>
                                <td class="text-end">$ <?= number_format($sumDescuento, 2) ?></td>
                            </tr>
                            <tr>
                                <th>IVA</th>
                                <td class="text-end">$ <?= number_format($sumIva, 2) ?></td>
                            </tr>
                            <tr class="table-dark">
                                <th>Total</th>
                                <td class="text-end">
                                    <strong>$ <?= number_format($sumTotal, 2) ?></strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>

</div>