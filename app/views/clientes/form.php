<div class="container mt-4">

    <h4 class="mb-3">
        <?= isset($data) ? '✏️ Editar' : '➕ Nuevo' ?> Cliente
    </h4>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST"
        action="index.php?page=clientes&action=<?= isset($data) ? 'edit' : 'create' ?>">

        <?php if (isset($data)): ?>
            <input type="hidden" name="id_cliente" value="<?= $data['id_cliente'] ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombres</label>
                <input type="text"
                    name="nomb_cliente"
                    class="form-control"
                    required
                    value="<?= $data['nomb_cliente'] ?? '' ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Apellidos</label>
                <input type="text"
                    name="apel_cliente"
                    class="form-control"
                    required
                    value="<?= $data['apel_cliente'] ?? '' ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Cédula</label>
                <input type="text"
                    name="cedu_cliente"
                    class="form-control"
                    pattern="\d{10}"
                    maxlength="10"
                    required
                    value="<?= $data['cedu_cliente'] ?? '' ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Celular</label>
                <input type="text"
                    name="celu_cliente"
                    pattern="\d{10}"
                    maxlength="10"
                    class="form-control"
                    value="<?= $data['celu_cliente'] ?? '' ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label>Ciudad</label>
                <select name="ciud_cliente" class="form-select" required>
                    <?php
                    $ciudades = [
                        'Guaranda',
                        'Caluma',
                        'Chillanes',
                        'Chimbo',
                        'Echeandía',
                        'Las Naves',
                        'San Miguel'
                    ];

                    $ciudadActual = $data['ciud_cliente'] ?? 'Guaranda';

                    foreach ($ciudades as $ciudad):
                    ?>
                        <option value="<?= $ciudad ?>"
                            <?= $ciudadActual === $ciudad ? 'selected' : '' ?>>
                            <?= $ciudad ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <textarea name="dire_cliente"
                class="form-control"
                rows="2"><?= $data['dire_cliente'] ?? '' ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Guardar
        </button>

        <a href="index.php?page=clientes" class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>