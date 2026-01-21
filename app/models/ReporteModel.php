<?php
require_once __DIR__ . '/../config/database.php';

class ReporteModel
{

    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function totalComprasVsVentas()
    {
        $sql = "
        SELECT
            (SELECT IFNULL(SUM(tota_compra),0) FROM t_compras WHERE esta_compra = 1) AS total_compras,
            (SELECT IFNULL(SUM(tota_venta),0) FROM t_ventas WHERE esta_venta = 1) AS total_ventas
    ";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function ventasMensuales()
    {
        $sql = "
            SELECT DATE_FORMAT(fech_venta,'%Y-%m') mes,
                   SUM(tota_venta) total
            FROM t_ventas
            WHERE esta_venta = 1
            GROUP BY mes
            ORDER BY mes
        ";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function productosMasVendidos()
    {
        $sql = "
            SELECT p.nomb_producto,vt.esta_venta, SUM(v.cant_vdetalle) cantidad
            FROM (t_vdetalle v
            INNER JOIN t_productos p ON p.id_producto = v.id_producto)
            INNER JOIN t_ventas as vt ON v.id_venta= vt.id_venta
            Where vt.esta_venta=1
            GROUP BY p.id_producto
            ORDER BY cantidad DESC
            LIMIT 10;
        ";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    //Reporte Compras vs Ventas -> por dia del mes actual
    public function comprasVsVentasMesActual()
    {
        $sql = "
        SELECT 
            d.fecha,
            IFNULL(c.total_compras, 0) AS compras,
            IFNULL(v.total_ventas, 0) AS ventas
        FROM (
            SELECT DATE(CURDATE() - INTERVAL (DAY(CURDATE())-1) DAY + INTERVAL n DAY) AS fecha
            FROM (
                SELECT 0 n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14
                UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19
                UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24
                UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29
                UNION ALL SELECT 30
            ) nums
        ) d
        LEFT JOIN (
            SELECT DATE(fech_factura) fecha, SUM(tota_compra) total_compras
            FROM t_compras
            WHERE esta_compra = 1
              AND MONTH(fech_factura) = MONTH(CURDATE())
              AND YEAR(fech_factura) = YEAR(CURDATE())
            GROUP BY fecha
        ) c ON c.fecha = d.fecha
        LEFT JOIN (
            SELECT fech_venta fecha, SUM(tota_venta) total_ventas
            FROM t_ventas
            WHERE esta_venta = 1
              AND MONTH(fech_venta) = MONTH(CURDATE())
              AND YEAR(fech_venta) = YEAR(CURDATE())
            GROUP BY fecha
        ) v ON v.fecha = d.fecha
        WHERE MONTH(d.fecha) = MONTH(CURDATE())
        ORDER BY d.fecha
        ";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
