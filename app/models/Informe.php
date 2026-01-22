<?php
require_once __DIR__ . '/../config/database.php';

class Informe
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /* ==================================================
       PRODUCTOS (PANTALLA)
    ================================================== */
    public function productosPaginados($limit, $offset)
    {
        $sql = "SELECT 
                    nomb_producto,
                    desc_producto,
                    precvent_producto,
                    stoc_producto
                FROM t_productos
                WHERE esta_producto = 1
                ORDER BY nomb_producto
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalProductos()
    {
        return $this->conn
            ->query("SELECT COUNT(*) FROM t_productos WHERE esta_producto = 1")
            ->fetchColumn();
    }

    /* ==================================================
       PRODUCTOS (PDF)
    ================================================== */
    public function productosTodos()
    {
        $sql = "SELECT 
                    nomb_producto,
                    desc_producto,
                    precvent_producto,
                    stoc_producto
                FROM t_productos
                WHERE esta_producto = 1
                ORDER BY nomb_producto";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==================================================
       COMPRAS POR FECHA
    ================================================== */
    public function comprasPorFecha($fechaIni, $fechaFin)
    {
        $sql = "SELECT
            nume_factura,
            fech_factura,
            subt_compra  AS subtotal,
            iva_compra   AS iva,
            tota_compra AS total,
            regi_compra
            FROM t_compras
            WHERE esta_compra = 1
              AND fech_factura BETWEEN ? AND ?
            ORDER BY fech_factura DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$fechaIni, $fechaFin]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================
   VENTAS POR RANGO DE FECHAS
===================================== */
    public function ventasPorFecha($fechaInicio, $fechaFin)
    {
        $sql = "
        SELECT 
            v.fech_venta,
            cl.nomb_cliente,
            cl.apel_cliente,
            v.subt_venta,
            v.desc_venta,
            v.iva_venta,
            v.tota_venta,
            u.nomb_usuario,
            v.regi_venta
        FROM (t_ventas v
        INNER JOIN t_usuarios u ON u.id_usuario = v.id_usuario)
        INNER JOIN t_clientes as cl ON v.id_cliente=cl.id_cliente
        WHERE v.esta_venta = 1
          AND v.fech_venta BETWEEN :fi AND :ff
        ORDER BY v.fech_venta DESC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fi', $fechaInicio);
        $stmt->bindParam(':ff', $fechaFin);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================
   TODAS LAS VENTAS (PDF)
===================================== */
    public function ventasTodas()
    {
        $sql = "
        SELECT 
        *
        FROM t_ventas
        WHERE esta_venta = 1
        ORDER BY fech_venta DESC
    ";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    //Ventas con paginacion
    /* ==================================================
   COMPRAS PAGINADAS POR FECHA
================================================== */
    public function comprasPaginadas($fechaIni, $fechaFin, $limit, $offset)
    {
        $sql = "SELECT
                nume_factura,
                fech_factura,
                subt_compra AS subtotal,
                iva_compra  AS iva,
                tota_compra AS total,
                regi_compra
            FROM t_compras
            WHERE esta_compra = 1
              AND fech_factura BETWEEN ? AND ?
            ORDER BY fech_factura DESC
            LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $fechaIni);
        $stmt->bindValue(2, $fechaFin);
        $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(4, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==================================================
   TOTAL DE COMPRAS EN RANGO
================================================== */
    public function totalCompras($fechaIni, $fechaFin)
    {
        $sql = "SELECT COUNT(*) 
            FROM t_compras
            WHERE esta_compra = 1
              AND fech_factura BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$fechaIni, $fechaFin]);
        return $stmt->fetchColumn();
    }

    /* ==================================================
   VENTAS PAGINADAS POR FECHA
================================================== */
    public function ventasPorFechaPaginadas($fechaIni, $fechaFin, $limit, $offset)
    {
        $sql = "
        SELECT 
            v.fech_venta,
            cl.nomb_cliente,
            cl.apel_cliente,
            u.nomb_usuario,
            v.subt_venta,
            v.desc_venta,
            v.iva_venta,
            v.tota_venta,
            v.regi_venta
        FROM (t_ventas v
        INNER JOIN t_usuarios u ON u.id_usuario = v.id_usuario)
        INNER JOIN t_clientes as cl ON v.id_cliente=cl.id_cliente
        WHERE v.esta_venta = 1
          AND v.fech_venta BETWEEN ? AND ?
        ORDER BY v.fech_venta DESC
        LIMIT ? OFFSET ?
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $fechaIni);
        $stmt->bindValue(2, $fechaFin);
        $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(4, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==================================================
   TOTAL DE VENTAS EN RANGO
================================================== */
    public function totalVentas($fechaIni, $fechaFin)
    {
        $sql = "SELECT COUNT(*) 
            FROM t_ventas
            WHERE esta_venta = 1
              AND fech_venta BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$fechaIni, $fechaFin]);
        return $stmt->fetchColumn();
    }
}
