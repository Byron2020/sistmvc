<?php
require_once __DIR__ . '/../config/database.php';

class Venta
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /* =========================================
       LISTADO SIMPLE (sin paginación)
    ========================================= */
    public function getAll()
    {
        $sql = "SELECT v.*, u.nomb_usuario
                FROM t_ventas v
                JOIN t_usuarios u ON v.id_usuario = u.id_usuario
                WHERE v.esta_venta = 1
                ORDER BY v.id_venta DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================
       LISTADO PAGINADO
    ========================================= */
    public function getPaginadas($limit, $offset)
    {
        $sql = "SELECT v.*, u.nomb_usuario
                FROM t_ventas v
                JOIN t_usuarios u ON v.id_usuario = u.id_usuario
                WHERE v.esta_venta = 1
                ORDER BY v.id_venta DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalRegistros()
    {
        $sql = "SELECT COUNT(*) FROM t_ventas WHERE esta_venta = 1";
        return $this->conn->query($sql)->fetchColumn();
    }

    /* =========================================
       GUARDAR VENTA + DETALLE (TRANSACCIÓN)
    ========================================= */
    public function guardarVenta($data)
    {
        try {
            $this->conn->beginTransaction();

            // 1️⃣ CABECERA
            $sql = "INSERT INTO t_ventas
                (id_usuario, fech_venta,clie_venta, subt_venta, iva_venta, tota_venta)
                VALUES (?, ?,? ,?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['usuario'],
                $data['fecha'],
                $data['cliente'],
                $data['subtotal'],
                $data['iva'],
                $data['total']
            ]);

            $idVenta = $this->conn->lastInsertId();

            // 2️⃣ DETALLE + STOCK
            foreach ($data['carrito'] as $p) {

                if ($p['cantidad'] > $p['stock']) {
                    throw new Exception("Stock insuficiente del producto {$p['nombre']}");
                }

                // Detalle
                $sqlDetalle = "INSERT INTO t_vdetalle
                    (id_venta, id_producto, cant_vdetalle, prec_vdetalle, tota_vdetalle)
                    VALUES (?, ?, ?, ?, ?)";

                $this->conn->prepare($sqlDetalle)->execute([
                    $idVenta,
                    $p['id'],
                    $p['cantidad'],
                    $p['precio'],
                    $p['cantidad'] * $p['precio']
                ]);

                // Descontar stock
                $sqlStock = "UPDATE t_productos
                    SET stoc_producto = stoc_producto - ?
                    WHERE id_producto = ?";

                $this->conn->prepare($sqlStock)->execute([
                    $p['cantidad'],
                    $p['id']
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /* =========================================
       DETALLE DE VENTA
    ========================================= */
    public function getDetalle($idVenta)
    {
        $sql = "SELECT d.*, p.nomb_producto
                FROM t_vdetalle d
                JOIN t_productos p ON d.id_producto = p.id_producto
                WHERE d.id_venta = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idVenta]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id)
    {
        $sql = "SELECT v.*, u.nomb_usuario
            FROM t_ventas v
            JOIN t_usuarios u ON v.id_usuario = u.id_usuario
            WHERE v.id_venta = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /* =========================================
       ANULAR VENTA (DEVUELVE STOCK)
    ========================================= */
    public function anular($idVenta)
    {
        try {
            $this->conn->beginTransaction();

            // Obtener detalle
            $detalle = $this->getDetalle($idVenta);

            // Devolver stock
            foreach ($detalle as $d) {
                $sql = "UPDATE t_productos
                        SET stoc_producto = stoc_producto + ?
                        WHERE id_producto = ?";
                $this->conn->prepare($sql)->execute([
                    $d['cant_vdetalle'],
                    $d['id_producto']
                ]);
            }

            // Marcar venta como anulada
            $sql = "UPDATE t_ventas
                    SET esta_venta = 0
                    WHERE id_venta = ?";
            $this->conn->prepare($sql)->execute([$idVenta]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
