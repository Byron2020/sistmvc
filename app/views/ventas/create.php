<!-- IZQUIERDA -->
<form method="POST"
    action="index.php?page=ventas&action=store"
    id="formVenta"
    onsubmit="return prepararVenta()">
    <div class="row">
        <!-- IZQUIERDA -->
        <div class="col-md-5">
            <h5 class="text-center"><strong> Detalle de Venta</strong></h5>
            <div class="d-flex">

                <div class="p-2 flex-grow-1">
                    <label>Cliente</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-badge"></i>
                        </span>

                        <select name="id_cliente" class="form-select" required>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id_cliente'] ?>"
                                    <?= $c['id_cliente'] == 1 ? 'selected' : '' ?>>
                                    <?= $c['nomb_cliente'] . ' ' . $c['apel_cliente'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="p-2">
                    <label>Fecha</label>
                    <input type="date" name="fech_venta" id="fech_venta" class="form-control"
                        value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
            <ul class="list-group mb-3" id="listaVenta"></ul>
            <hr>
            <!-- SUBTOTAL -->
            <div class="d-flex justify-content-between">
                <strong>Subtotal:</strong>
                <span>$ <span id="subtotal">0.00</span></span>
            </div>
            <!-- DESCUENTO -->
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="d-flex align-items-center gap-2">
                    <strong>Descuento (%)</strong>
                    <input type="number"
                        id="descuento"
                        class="form-control form-control-sm"
                        style="width: 70px;"
                        value="0"
                        step="any"
                        min="0"
                        oninput="renderCarrito()">
                </div>

                <span>$ <span id="descuentoMonto">0.00</span></span>
            </div>
            <!-- IVA -->
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="d-flex align-items-center gap-2">
                    <strong>IVA (%)</strong>
                    <input type="number"
                        id="iva"
                        class="form-control form-control-sm"
                        style="width: 70px;"
                        value="0"
                        step="any"
                        min="0"
                        oninput="renderCarrito()">
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
                <i class="bi bi-save"></i> Guardar Venta
            </button>

            <a href="index.php?page=ventas" class="btn btn-secondary w-100 mt-3">
                Cancelar
            </a>
        </div>
        <input type="hidden" name="carrito" id="carritoInput">
        <input type="hidden" name="subtotal" id="inputSubtotal">
        <input type="hidden" name="iva" id="inputIva">
        <input type="hidden" name="iva_monto" id="inputIvaMonto">
        <input type="hidden" name="total" id="inputTotal">
        <input type="hidden" name="descuento" id="inputDescuento">
        <input type="hidden" name="desc_monto" id="inputDescuentoMonto">
</form>
<!-- DERECHA -->
<div class="col-md-7">
    <h4>Productos</h4>
    <input type="text" class="form-control mb-2"
        placeholder="Buscar por nombre, descripción o stock"
        id="buscador">

    <table class="table table-sm table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Detalle</th>
                <th>Stock</th>
                <th>Precio</th>
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
<!--Modal para Cantidad-->
<div class="modal fade" id="modalProducto">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="modalNombre"></h5>
            </div>

            <div class="modal-body">
                <p>Precio: $<span id="modalPrecio"></span></p>
                <p>Stock: <span id="modalStock"></span></p>

                <label>Cantidad</label>
                <input type="number" id="modalCantidad"
                    class="form-control" min="1">
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
    /* ===== REFERENCIAS DOM ===== */
    const modalNombre = document.getElementById('modalNombre');
    const modalPrecio = document.getElementById('modalPrecio');
    const modalStock = document.getElementById('modalStock');
    const modalCantidad = document.getElementById('modalCantidad');

    const listaVenta = document.getElementById('listaVenta');

    const subtotalSpan = document.getElementById('subtotal');
    const ivaMontoSpan = document.getElementById('ivaMonto');
    const totalSpan = document.getElementById('total');

    const inputSubtotal = document.getElementById('inputSubtotal');
    const inputIvaMonto = document.getElementById('inputIvaMonto');
    const inputTotal = document.getElementById('inputTotal');
    const carritoInput = document.getElementById('carritoInput');

    const descuentoMontoSpan = document.getElementById('descuentoMonto');
    const inputDescuento = document.getElementById('inputDescuento');
    const inputDescuentoMonto = document.getElementById('inputDescuentoMonto');

    let carrito = [];
    let actual = {};
    const IVA = 0;
</script>

<script>
    const limit = 7;
    let pagina = 1;
    let filtro = '';

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
          <td class="text-end">${p.precvent_producto}</td>
          <td class="text-center">
            <button type="button" class="btn btn-success btn-sm"
              onclick="abrirModal(${p.id_producto}, '${p.nomb_producto}', ${p.precvent_producto}, ${p.stoc_producto})">
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

<!--Javascript-->
<script>
    //Validar stock
    function abrirModal(id, nombre, precio, stock) {
        actual = {
            id,
            nombre,
            precio,
            stock
        };
        modalNombre.innerText = nombre;
        modalPrecio.innerText = precio;
        modalStock.innerText = stock;
        modalCantidad.value = 1;
        new bootstrap.Modal('#modalProducto').show();
    }
    //Agregar Producto
    function agregarProducto() {

        const cantidad = parseInt(modalCantidad.value);

        if (cantidad <= 0) {
            alert('Cantidad inválida');
            return;
        }

        if (cantidad > actual.stock) {
            alert('Stock insuficiente');
            return;
        }

        const existe = carrito.find(p => p.id === actual.id);

        if (existe) {
            existe.cantidad += cantidad;
        } else {
            carrito.push({
                id: actual.id,
                nombre: actual.nombre,
                precio: actual.precio,
                stock: actual.stock,
                cantidad
            });
        }

        renderCarrito();

        bootstrap.Modal
            .getInstance(document.getElementById('modalProducto'))
            .hide();
    }

    //Totales
    function renderCarrito() {

        listaVenta.innerHTML = '';
        let subtotal = 0;

        carrito.forEach((p, i) => {

            const totalProducto = p.precio * p.cantidad;
            subtotal += totalProducto;

            listaVenta.innerHTML += `
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>
                ${p.cantidad} 
                <strong>${p.nombre}</strong> 
                ($${p.precio.toFixed(2)})
            </span>

            <span>
                <strong>$${totalProducto.toFixed(2)}</strong>
                <button type="button" class="btn btn-sm btn-danger ms-2"
                        onclick="quitarProducto(${i})">
                    <i class="bi bi-trash"></i>
                </button>
            </span>
        </li>`;
        });

        const ivaPorcentaje = parseFloat(document.getElementById('iva').value) || 0;
        const descuentoPorcentaje = parseFloat(document.getElementById('descuento').value) || 0;

        const descuentoMonto = subtotal * descuentoPorcentaje / 100;
        const baseConDescuento = subtotal - descuentoMonto;

        const ivaMonto = baseConDescuento * ivaPorcentaje / 100;
        const total = baseConDescuento + ivaMonto;

        subtotalSpan.innerText = subtotal.toFixed(2);
        descuentoMontoSpan.innerText = descuentoMonto.toFixed(2);
        ivaMontoSpan.innerText = ivaMonto.toFixed(2);
        totalSpan.innerText = total.toFixed(2);

        inputSubtotal.value = subtotal.toFixed(2);
        inputDescuento.value = descuentoPorcentaje;
        inputDescuentoMonto.value = descuentoMonto.toFixed(2);
        inputIvaMonto.value = ivaMonto.toFixed(2);
        inputTotal.value = total.toFixed(2);
    }

    //Enviar datos de Venta
    function prepararVenta() {
        if (carrito.length === 0) {
            alert('Agregue productos');
            return false;
        }

        carritoInput.value = JSON.stringify(carrito);
        return true;
    }

    function quitarProducto(index) {
        carrito.splice(index, 1);
        renderCarrito();
    }
</script>