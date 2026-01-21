<div class="container mt-4">

    <h3 class="mb-3">Cálculo de costo unitario (con IVA)</h3>

    <form method="POST" action="index.php?page=factura&action=calcular" id="formFactura">

        <table class="table table-bordered" id="tablaProductos">
            <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" name="producto[]" class="form-control" value="Producto 1" readonly>
                    </td>
                    <td>
                        <input type="number" step="1" min="1" name="cantidad[]" class="form-control" required>
                    </td>
                    <td>
                        <input type="number" step="0.00001" name="precio[]" class="form-control" required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                            ✖
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-primary mb-3" onclick="agregarFila()">
            ➕ Agregar producto
        </button>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Total factura (con IVA)</label>
                <input type="number" step="0.00001" name="total_factura" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success">
            Calcular costos
        </button>

    </form>

</div>

<script>
let contador = 1;

function agregarFila() {
    contador++;
    const tabla = document.querySelector("#tablaProductos tbody");

    const fila = `
        <tr>
            <td>
                <input type="text" name="producto[]" class="form-control" value="Producto ${contador}" readonly>
            </td>
            <td>
                <input type="number" step="0.00001" name="cantidad[]" class="form-control" required>
            </td>
            <td>
                <input type="number" step="0.00001" name="precio[]" class="form-control" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                    ✖
                </button>
            </td>
        </tr>
    `;
    tabla.insertAdjacentHTML("beforeend", fila);
}

function eliminarFila(btn) {
    const filas = document.querySelectorAll("#tablaProductos tbody tr");
    if (filas.length > 1) {
        btn.closest("tr").remove();
    }
}
</script>
