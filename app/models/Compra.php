<?php
require_once __DIR__ . '/../config/database.php';
require_once 'app/config/config.php';

class Compra
{

    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll()
    {
        $sql = "SELECT c.*, u.nomb_usuario AS usuario
            FROM t_compras c
            LEFT JOIN t_usuarios u ON c.id_usuario = u.id_usuario
            WHERE c.esta_compra = 1
            ORDER BY c.id_compra DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countAll()
    {
        $sql = "SELECT COUNT(*) FROM t_compras WHERE esta_compra = 1";
        return $this->conn->query($sql)->fetchColumn();
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT c.*, u.nomb_usuario AS usuario
            FROM t_compras c
            LEFT JOIN t_usuarios u ON c.id_usuario = u.id_usuario
            WHERE c.esta_compra = 1
            ORDER BY c.id_compra DESC
            LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarCompra($data)
    {
        try {
            $this->conn->beginTransaction();

            // 1️⃣ CABECERA
            $sql = "INSERT INTO t_compras 
            (id_usuario,nume_factura, fech_factura, subt_compra, iva_compra, tota_compra)
            VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['usuario'],
                $data['nume_factura'],
                $data['fech_factura'],
                $data['subt_compra'],
                $data['iva_compra'],
                $data['tota_compra']

            ]);

            $idCompra = $this->conn->lastInsertId();

            // 2️⃣ DETALLE + STOCK
            foreach ($data['carrito'] as $p) {

                // detalle
                $sqlDetalle = "INSERT INTO t_cdetalle
                (id_compra, id_producto, cant_cdetalle, prec_cdetalle, tota_cdetalle)
                VALUES (?, ?, ?, ?, ?)";

                $this->conn->prepare($sqlDetalle)->execute([
                    $idCompra,
                    $p['id'],
                    $p['cantidad'],
                    $p['precio'],
                    $p['cantidad'] * $p['precio']
                ]);

                // stock
                $sqlStock = "UPDATE t_productos
                SET stoc_producto = stoc_producto + ?
                WHERE id_producto = ?";

                $this->conn->prepare($sqlStock)->execute([
                    $p['cantidad'],
                    $p['id']
                ]);
            }

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            die("Error al guardar compra: " . $e->getMessage());
        }
    }

    public function getById($id)
    {
        $sql = "SELECT c.*, u.nomb_usuario 
            FROM t_compras c
            JOIN t_usuarios u ON c.id_usuario = u.id_usuario
            WHERE c.id_compra = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetalle($id)
    {
        $sql = "SELECT d.*, p.nomb_producto
            FROM t_cdetalle d
            JOIN t_productos p ON d.id_producto = p.id_producto
            WHERE d.id_compra = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function anularCompra($id)
    {
        try {
            $this->conn->beginTransaction();

            // 1️ Obtener detalle
            $sql = "SELECT id_producto, cant_cdetalle 
                FROM t_cdetalle 
                WHERE id_compra = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 2️ Revertir stock
            foreach ($detalles as $d) {
                $sql = "UPDATE t_productos 
                    SET stoc_producto = stoc_producto - ?
                    WHERE id_producto = ?";
                $this->conn->prepare($sql)->execute([
                    $d['cant_cdetalle'],
                    $d['id_producto']
                ]);
            }

            // 3 Marcar compra anulada
            $sql = "UPDATE t_compras 
                SET esta_compra = 0 
                WHERE id_compra = ?";
            $this->conn->prepare($sql)->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
