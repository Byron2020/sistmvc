<?php
class FacturaModel {

    public function calcularConPrecio($productos, $cantidades, $precios, $totalFactura) {

        $detalle = [];
        $subtotal = 0;

        for ($i = 0; $i < count($productos); $i++) {
            $subtotal += bcmul($cantidades[$i], $precios[$i], 5);
        }

        $iva_total = bcsub($totalFactura, $subtotal, 5);

        for ($i = 0; $i < count($productos); $i++) {

            $total_producto = bcmul($cantidades[$i], $precios[$i], 5);
            $proporcion = bcdiv($total_producto, $subtotal, 10);
            $iva_producto = bcmul($iva_total, $proporcion, 5);
            $costo_unitario = bcdiv(
                bcadd($total_producto, $iva_producto, 5),
                $cantidades[$i],
                5
            );

            $detalle[] = [
                'producto' => $productos[$i],
                'cantidad' => $cantidades[$i],
                'costo_unitario' => $costo_unitario,
                'iva_producto' => $iva_producto
            ];
        }

        return [
            'subtotal' => $subtotal,
            'iva_total' => $iva_total,
            'total' => $totalFactura,
            'detalle' => $detalle
        ];
    }
}
