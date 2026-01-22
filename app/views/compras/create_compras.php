<form method="POST"
    action="index.php?page=compras&action=store"
    id="formCompra"
    onsubmit="return prepararCompra()">
    <div class="row">
        <!-- IZQUIERDA -->
        <div class="col-md-5">
            <h5 class="text-center"><strong>Detalle de Compra</strong></h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>N° Factura</label>
                    <input type="text" name="nume_factura" id="nume_factura" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Fecha de Factura</label>
                    <input type="date" name="fech_factura" id="fech_factura" class="form-control"
                        value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <ul class="list-group mb-3" id="listaCompra"></ul>
            <hr>
            <!-- SUBTOTAL -->
            <div class="d-flex justify-content-between">
                <strong>Subtotal:</strong>
                <span>$ <span id="subtotal">0.00</span></span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="d-flex align-items-center gap-2">
                    <strong>IVA (%)</strong>
                    <input type="number"
                        id="iva"
                        class="form-control form-control-sm"
                        style="width: 70px;"
                        value="15"
                        step="any"
                        min="0"
                        oninput="calcularTotales()">
                </div>
                <span>$ <span id="ivaMonto">0.00</span></span>
            </div>
            <hr>

            <!-- TOTAL -->
            <div class="d-flex justify-content-between fs-5">
                <strong>Total:</strong>
                <strong>$ <span id="total">0.00</span></strong>
            </div>
            <!-- GUARDAR -->
            <button type="submit" class="btn btn-primary w-100 mt-3">
                <i class="bi bi-save"></i> Guardar Compra
            </button>

            <a href="index.php?page=compras" class="btn btn-secondary w-100 mt-3">
                Cancelar
            </a>
        </div>
        <input type="hidden" name="carrito" id="carritoInput">
        <input type="hidden" name="subtotal" id="inputSubtotal">
        <input type="hidden" name="iva" id="inputIva">
        <input type="hidden" name="iva_monto" id="inputIvaMonto">
        <input type="hidden" name="total" id="inputTotal">

</form>
<!-- DERECHA -->
<div class="col-md-7">
    <h4>Lista de productos</h4>
    <input type="text" class="form-control mb-2"
        placeholder="Buscar producto..."
        id="buscador">

    <table class="table table-sm table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Detalle</th>
                <th>Stock</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="tablaProductos"></tbody>
    </table>

    <nav>
        <ul class="pagination justify-content-end" id="paginacion"></ul>
    </nav>

</div>

</div>

<!-- MODAL PARA CANTIDAD -->

<div class="modal fade" id="modalProducto">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="modalNombre"></h5>
            </div>

            <div class="modal-body">
                <div class="mb-2">
                    <label>Cantidad</label>
                    <input type="number" id="modalCantidad" class="form-control" min="1" required="true">
                    <label>Precio de compra</label>
                    <input type="number" step="0.01" id="modalPrecio" class="form-control" required="true">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="agregarProducto()">Agregar</button>
            </div>

        </div>
    </div>
</div>



<!-- pasar PHP a Javascript -->
<script>
    const productos = <?= json_encode($productos) ?>;
</script>

<!-- PAGINACION EN jAVA -->
<script>
    const limit = 5;
    let pagina = 1;
    let filtro = '';
    let carrito = [];

    function renderProductos() {

        let filtrados = productos.filter(p =>
            p.nomb_producto.toLowerCase().includes(filtro) ||
            p.desc_producto.toLowerCase().includes(filtro)
        );

        let inicio = (pagina - 1) * limit;
        let visibles = filtrados.slice(inicio, inicio + limit);

        const tbody = document.getElementById('tablaProductos');
        tbody.innerHTML = '';

        visibles.forEach(p => {
            tbody.innerHTML += `
        <tr>
          <td>${p.nomb_producto}</td>
          <td>${p.desc_producto}</td>
          <td class="text-end">${p.stoc_producto}</td>
          <td class="text-center">
            <button type="button" class="btn btn-success btn-sm"
              onclick="abrirModal(${p.id_producto}, '${p.nomb_producto}')">
              <i class="bi bi-plus"></i>
            </button>
          </td>
        </tr>`;
        });

        renderPaginacion(filtrados.length);
    }

    function renderPaginacion(total) {
        const paginas = Math.ceil(total / limit);
        const ul = document.getElementById('paginacion');
        ul.innerHTML = '';

        for (let i = 1; i <= paginas; i++) {
            ul.innerHTML += `
      <li class="page-item ${i === pagina ? 'active' : ''}">
        <a class="page-link" href="#" onclick="pagina=${i};renderProductos()">${i}</a>
      </li>`;
        }
    }

    document.getElementById('buscador').addEventListener('keyup', e => {
        filtro = e.target.value.toLowerCase();
        pagina = 1;
        renderProductos();
    });

    renderProductos();
</script>
<!-- CARRITO + IVA + TOTAL -->
<script>
    let actual = {};

    function prepararCompra() {

        if (carrito.length === 0) {
            alert('Debe agregar al menos un producto');
            return false;
        }

        // FUERZA EL CÁLCULO ANTES DE ENVIAR
        calcularTotales();
        document.getElementById('carritoInput').value = JSON.stringify(carrito);
        return true; // permite enviar el form
    }

    function abrirModal(id, nombre, precio) {
        actual = {
            id,
            nombre
        };
        document.getElementById('modalNombre').innerText = nombre;
        document.getElementById('modalCantidad').value = 1;
        new bootstrap.Modal('#modalProducto').show();
    }

    function quitarProducto(index) {
        carrito.splice(index, 1);
        renderCarrito();
    }

    function agregarProducto() {

        actual.precio = parseFloat(document.getElementById('modalPrecio').value);
        actual.cantidad = parseInt(document.getElementById('modalCantidad').value);

        let existe = carrito.find(p => p.id === actual.id);

        if (existe) {
            existe.cantidad += actual.cantidad;
            existe.precio = actual.precio; // actualiza precio si cambia
        } else {
            carrito.push({
                ...actual
            });
        }

        renderCarrito();

        bootstrap.Modal.getInstance(
            document.getElementById('modalProducto')
        ).hide();
    }


    function calcularTotales() {

        let subtotal = 0;

        carrito.forEach(p => {
            subtotal += p.precio * p.cantidad;
        });

        const ivaPorcentaje = parseFloat(document.getElementById('iva').value) || 0;
        const ivaMonto = subtotal * ivaPorcentaje / 100;
        const total = subtotal + ivaMonto;

        // Mostrar en pantalla
        document.getElementById('subtotal').innerText = subtotal.toFixed(2);
        document.getElementById('ivaMonto').innerText = ivaMonto.toFixed(2);
        document.getElementById('total').innerText = total.toFixed(2);

        // Enviar al backend
        document.getElementById('inputSubtotal').value = subtotal.toFixed(2);
        document.getElementById('inputIva').value = ivaPorcentaje;
        document.getElementById('inputIvaMonto').value = ivaMonto.toFixed(2);
        document.getElementById('inputTotal').value = total.toFixed(2);
    }


    function renderCarrito() {

        const ul = document.getElementById('listaCompra');
        ul.innerHTML = '';

        let subtotal = 0;

        carrito.forEach((p, i) => {

            let totalProducto = p.precio * p.cantidad;
            subtotal += totalProducto;

            ul.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                    ${p.cantidad}
                    <strong>${p.nombre}</strong>
                    (${p.precio.toFixed(2)})
                </span>

                <span>
                    <strong>${totalProducto.toFixed(2)}$</strong>
                    <button  type="button" class="btn btn-sm btn-danger ms-2"
                            onclick="quitarProducto(${i})">
                        <i class="bi bi-trash"></i>
                    </button>
                </span>
            </li>`;
        });

        document.getElementById('subtotal').innerText = subtotal.toFixed(2);

        const iva = parseFloat(document.getElementById('iva').value) || 0;
        const ivaMonto = subtotal * iva / 100;
        const total = subtotal + ivaMonto;

        document.getElementById('ivaMonto').innerText = ivaMonto.toFixed(2);
        document.getElementById('total').innerText = total.toFixed(2);
        document.getElementById('inputSubtotal').value = subtotal.toFixed(2);
        document.getElementById('inputIva').value = iva;
        document.getElementById('inputTotal').value = total.toFixed(2);
    }
</script>