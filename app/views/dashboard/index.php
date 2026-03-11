<?php
// app/views/dashboard/index.php - Panel para usuarios logueados
?>
<div class="container">
    <h1>Bienvenido al Sistema</h1>
    <p>Has iniciado sesión como: <strong><?= htmlspecialchars($_SESSION['rol'] ?? 'Usuario') ?></strong></p>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Productos</div>
                <div class="card-body">
                    <h5 class="card-title">Gestión de inventario</h5>
                    <a href="index.php?page=productos" class="btn btn-light">Ir</a>
                </div>
            </div>
        </div>
        <!-- Más tarjetas según el rol -->
    </div>
</div>