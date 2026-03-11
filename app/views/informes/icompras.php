<?php
// Fechas por defecto
date_default_timezone_set('America/Guayaquil');
$fechaFinDefault = date('Y-m-d');
$fechaInicioDefault = date('Y-m-d', strtotime('-1 month'));
// strtotime maneja automáticamente febrero, meses de 28/30/31 días
?>

<div class="container mt-4">

    <!-- =========================
         BOTONES SUPERIORES
    ========================== -->
    <div class="d-flex justify-content-between mb-3">
        <a href="index.php?page=informes&action=pdf&tipo=compras&fi=<?= $fechaIni ?>&ff=<?= $fechaFin ?>"
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
                <input type="hidden" name="action" value="icompras">

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
         LISTADO DE COMPRAS
    ========================== -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Listado de Compras</h5>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Num. Factura</th>
                        <th>Fecha Compra</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">IVA</th>
                        <th class="text-end">Total</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (!empty($compras)): ?>
                        <?php
                        $i = 1;
                        $sumSubtotal = 0;
                        $sumIva = 0;
                        $sumTotal = 0;
                        ?>
                        <?php foreach ($compras as $c): ?>
                            <?php
                            $sumSubtotal += $c['subtotal'];
                            $sumIva      += $c['iva'];
                            $sumTotal    += $c['total'];
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($c['nume_factura']) ?></td>
                                <td><?= htmlspecialchars($c['fech_factura']) ?></td>
                                <td class="text-end">$ <?= number_format($c['subtotal'], 2) ?></td>
                                <td class="text-end">$ <?= number_format($c['iva'], 2) ?></td>
                                <td class="text-end">$ <?= number_format($c['total'], 2) ?></td>
                                <td><?= htmlspecialchars($c['regi_compra']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                No hay compras en el rango seleccionado
                            </td>
                        </tr>
                    <?php endif ?>

                </tbody>
            </table>
            <!--Paginacon -->
            <?php if ($paginas > 1): ?>
                <nav aria-label="Paginación">
                    <ul class="pagination justify-content-center mt-3">

                        <!-- Botón ANTERIOR -->
                        <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=informes&action=icompras&fi=<?= $fechaIni ?>&ff=<?= $fechaFin ?>&p=<?= max(1, $pagina - 1) ?>">
                                &laquo; Anterior
                            </a>
                        </li>

                        <?php
                        // Definir el rango de páginas visibles (máximo 5)
                        $maxVisible = 5;
                        $start = max(1, $pagina - 2);
                        $end = min($paginas, $start + $maxVisible - 1);

                        // Ajustar start si estamos al final
                        if ($end - $start + 1 < $maxVisible) {
                            $start = max(1, $end - $maxVisible + 1);
                        }

                        for ($p = $start; $p <= $end; $p++): ?>
                            <li class="page-item <?= ($p == $pagina) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=informes&action=icompras&fi=<?= $fechaIni ?>&ff=<?= $fechaFin ?>&p=<?= $p ?>">
                                    <?= $p ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Botón SIGUIENTE -->
                        <li class="page-item <?= ($pagina >= $paginas) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=informes&action=icompras&fi=<?= $fechaIni ?>&ff=<?= $fechaFin ?>&p=<?= min($paginas, $pagina + 1) ?>">
                                Siguiente &raquo;
                            </a>
                        </li>

                    </ul>
                </nav>
            <?php endif; ?>

            <!-- =========================
                 TOTALES
            ========================== -->
            <?php if (!empty($compras)): ?>
                <div class="row justify-content-end mt-3">
                    <div class="col-md-4">
                        <table class="table table-bordered">
                            <tr>
                                <th>Subtotal</th>
                                <td class="text-end">$ <?= number_format($sumSubtotal, 2) ?></td>
                            </tr>
                            <tr>
                                <th>IVA</th>
                                <td class="text-end">$ <?= number_format($sumIva, 2) ?></td>
                            </tr>
                            <tr class="table-dark">
                                <th>Total</th>
                                <td class="text-end">$ <?= number_format($sumTotal, 2) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>

</div>