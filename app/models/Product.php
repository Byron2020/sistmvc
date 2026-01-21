<?php
require_once __DIR__ . '/../config/database.php';

class Product
{

    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /* ===============================
       LISTAR
    ================================ */
    public function getAll()
    {
        $sql = "SELECT * 
                FROM t_productos 
                WHERE esta_producto = 1 Order by nomb_producto";
        return $this->conn->query($sql);
    }

    /* ===============================
       OBTENER POR ID
    ================================ */
    public function getById($id)
    {
        $sql = "SELECT * 
                FROM t_productos 
                WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ===============================
       CREAR
    ================================ */
    public function create($data)
    {
        $sql = "INSERT INTO t_productos
                (nomb_producto, desc_producto, precvent_producto, stoc_producto)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    /* ===============================
       ACTUALIZAR
    ================================ */
    public function update($data)
    {
        $sql = "UPDATE t_productos 
                SET nomb_producto = ?, 
                    desc_producto = ?,  
                    precvent_producto = ?, 
                    stoc_producto = ?
                WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    /* ===============================
       ELIMINAR (LÓGICO)
    ================================ */
    public function delete($id)
    {
        $sql = "UPDATE t_productos 
                SET esta_producto = 0 
                WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT * 
            FROM t_productos
            WHERE esta_producto = 1
            ORDER BY id_producto DESC
            LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) FROM t_productos WHERE esta_producto = 1";
        return $this->conn->query($sql)->fetchColumn();
    }
}
