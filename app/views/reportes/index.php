<div class="container mt-4">

    <h3 class="mb-4">Gráficas del sistema</h3>

    <div class="row g-4">

        <!-- Compras vs Ventas -->
        <div class="col-md-3">
            <a href="index.php?page=reportes&action=grafica&tipo=comparativo" class="text-decoration-none">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-graph-up display-4 text-primary"></i>
                        <h5 class="mt-3">Compras vs Ventas</h5>
                        <p class="text-muted">Comparativo de movimientos</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Ventas Mensuales -->
        <div class="col-md-3">
            <a href="index.php?page=reportes&action=grafica&tipo=ventas" class="text-decoration-none">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-bar-chart display-4 text-success"></i>
                        <h5 class="mt-3">Ventas Mensuales</h5>
                        <p class="text-muted">Resumen por mes</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Productos más vendidos -->
        <div class="col-md-3">
            <a href="index.php?page=reportes&action=grafica&tipo=productos" class="text-decoration-none">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-pie-chart display-4 text-warning"></i>
                        <h5 class="mt-3">Productos más vendidos</h5>
                        <p class="text-muted">Ranking de productos</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Totales Generales -->
        <div class="col-md-3">
            <a href="index.php?page=reportes&action=grafica&tipo=barras" class="text-decoration-none">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-bar-chart-fill display-4 text-danger"></i>
                        <h5 class="mt-3">Totales Generales</h5>
                        <p class="text-muted">Resumen completo</p>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>
