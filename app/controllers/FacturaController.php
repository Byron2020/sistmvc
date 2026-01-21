<?php
require_once 'app/models/FacturaModel.php';

class FacturaController {

    // Muestra formulario
    public function index() {
        require 'app/views/factura/index.php';
    }

    // Procesa POST
    public function calcular() {

        $productos  = $_POST['producto'];
        $cantidades = $_POST['cantidad'];
        $precios    = $_POST['precio'];
        $total_factura = $_POST['total_factura'];

        $model = new FacturaModel();

        $data = $model->calcularConPrecio(
            $productos,
            $cantidades,
            $precios,
            $total_factura
        );

        // ✅ AQUÍ VA
        $_SESSION['resultado_factura'] = $data;
        header("Location: index.php?page=factura&action=resultado");
        exit;
    }

    // Muestra resultado con layout
    public function resultado() {
        $data = $_SESSION['resultado_factura'] ?? null;
        unset($_SESSION['resultado_factura']);

        require 'app/views/factura/resultado.php';
    }
}
