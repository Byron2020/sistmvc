<div class="container">
    <h4><?= isset($data) ? '✏️ Editar' : '➕ Nuevo' ?> Producto</h4>

    <form method="POST">

        <?php if (isset($data)): ?>
            <input type="hidden" name="id_producto" value="<?= $data['id_producto'] ?>">
        <?php endif; ?>

        <div class="mb-2">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required
                   value="<?= $data['nomb_producto'] ?? '' ?>">
        </div>

        <div class="mb-2">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control"><?= $data['desc_producto'] ?? '' ?></textarea>
        </div>

        <div class="mb-2">
            <label>Precio Venta</label>
            <input type="number" step="0.01" name="preciovent" class="form-control" required placeholder="0.01"
                   value="<?= $data['precvent_producto'] ?? '' ?>">
        </div>

        <div class="mb-2">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required placeholder="0"
                   value="<?= $data['stoc_producto'] ?? '' ?>">
        </div>

        <button class="btn btn-success">
            <i class="bi bi-save"></i> Guardar
        </button>

        <a href="index.php?page=productos" class="btn btn-secondary">
            Cancelar
        </a>
    </form>
</div>
