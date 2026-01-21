<h2><?= $titulo ?></h2>

<canvas id="grafico" height="120"></canvas>

<hr>
<form id="formPDF" method="POST" target="_blank" action="index.php?page=reportes&action=pdf"
    onsubmit="return enviarGrafico();" class="text-center">
    <input type="hidden" name="imagen" id="imagen">
    <input type="hidden" name="titulo" value="<?= $titulo ?>">
    <button type="submit" class="btn btn-danger">
        Generar PDF
    </button>
</form>



<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Generar pdf GRAFICAS -->
<script>
    function enviarGrafico() {

        if (!chart) {
            alert('El gráfico aún no está listo');
            return false;
        }

        const imagen = chart.toBase64Image();
        document.getElementById('imagen').value = imagen;

        // ⬅️ dejar que el submit continúe
        return true;
    }
</script>

<script>
    const tipo = "<?= $_GET['tipo'] ?>";
    const dataPHP = <?= json_encode($dataGrafico) ?>;


    const ctx = document.getElementById('grafico').getContext('2d');
    let chartConfig = null;

    /* =========================
       FUNCIÓN OPCIONES $
    ========================= */
    function opcionesDolares() {
        return {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '$ ' + value.toLocaleString('en-US', {
                            minimumFractionDigits: 2
                        })
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => '$ ' + ctx.parsed.y.toLocaleString('en-US', {
                            minimumFractionDigits: 2
                        })
                    }
                }
            }
        };
    }

    /* =========================
       COMPARATIVO (LINEAS)
    ========================= */
    if (tipo === 'comparativo') {

        let labels = [];
        let compras = [];
        let ventas = [];

        dataPHP.forEach(item => {
            labels.push(item.fecha);
            compras.push(item.compras);
            ventas.push(item.ventas);
        });

        chartConfig = {
            type: 'line',
            data: {
                labels,
                datasets: [{
                        label: 'Compras',
                        data: compras,
                        borderColor: 'rgb(236, 49, 68)',
                        tension: 0.3
                    },
                    {
                        label: 'Ventas',
                        data: ventas,
                        borderColor: 'rgb(14, 211, 119)',
                        tension: 0.3
                    }
                ]
            },
            options: opcionesDolares()
        };
    }

    /* =========================
       BARRAS TOTALES GENERALES
    ========================= */
    if (tipo === 'barras') {

        const compras = dataPHP.total_compras;
        const ventas = dataPHP.total_ventas;
        const diferencia = compras - ventas;

        chartConfig = {
            type: 'bar',
            data: {
                labels: ['Compras', 'Ventas', 'Diferencia'],
                datasets: [{
                    label: 'Totales Generales ($)',
                    data: [compras, ventas, diferencia],
                    backgroundColor: [
                        'rgb(255, 159, 64)', // rojo
                        'rgba(8, 92, 53, 0.93)', // verde
                        'rgba(13, 109, 253, 0.85)' // azul
                    ]
                }]
            },
            options: opcionesDolares()
        };
    }

    /* =========================
       VENTAS / PRODUCTOS / STOCK
    ========================= */
    if (['ventas', 'productos', 'stock'].includes(tipo)) {

        let labels = [];
        let valores = [];

        if (tipo === 'ventas') {
            dataPHP.forEach(i => {
                labels.push(i.mes);
                valores.push(i.total);
            });
        }

        if (tipo === 'productos') {
            dataPHP.forEach(i => {
                labels.push(i.nomb_producto);
                valores.push(i.cantidad);
            });
        }

        if (tipo === 'stock') {
            dataPHP.forEach(i => {
                labels.push(i.nomb_producto);
                valores.push(i.stoc_producto);
            });
        }

        chartConfig = {
            type: tipo === 'ventas' ? 'line' : 'bar',
            data: {
                labels,
                datasets: [{
                    label: "<?= $titulo ?>",
                    data: valores,
                    backgroundColor: 'rgba(13,110,253,0.6)'
                }]
            },
            options: opcionesDolares()
        };
    }

    /* =========================
       DIBUJAR GRÁFICO
    ========================= */
    let chart;

    if (chartConfig) {
        chart = new Chart(ctx, chartConfig);
    }
</script>