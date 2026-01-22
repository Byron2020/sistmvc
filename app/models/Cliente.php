<?php

require_once 'app/config/database.php';

class Cliente
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /* =========================
       CONTAR CLIENTES
    ========================== */
    public function count()
    {
        $sql = "SELECT COUNT(*) FROM t_clientes WHERE esta_cliente = 1";
        return $this->conn->query($sql)->fetchColumn();
    }

     //Clientes 
    public function getActivos()
    {
        $sql = "SELECT id_cliente, nomb_cliente, apel_cliente
            FROM t_clientes
            WHERE esta_cliente = 1
            ORDER BY nomb_cliente";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /* =========================
       LISTAR CLIENTES PAGINADOS
    ========================== */
    public function getAll($limit, $offset)
    {
        $sql = "
            SELECT *
            FROM t_clientes
            WHERE esta_cliente = 1
            ORDER BY nomb_cliente
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       INSERTAR CLIENTE
    ========================== */
    public function insert($data)
    {
        $sql = "
            INSERT INTO t_clientes
            (nomb_cliente, apel_cliente, cedu_cliente, celu_cliente, ciud_cliente, dire_cliente, esta_cliente)
            VALUES
            (:nomb, :apel, :cedu, :celu, :ciud, :dire, :esta)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':nomb' => $data['nomb_cliente'],
            ':apel' => $data['apel_cliente'],
            ':cedu' => $data['cedu_cliente'],
            ':celu' => $data['celu_cliente'],
            ':ciud' => $data['ciud_cliente'],
            ':dire' => $data['dire_cliente'],
            ':esta' => $data['esta_cliente'],
        ]);
    }

    /* =========================
       OBTENER CLIENTE POR ID
    ========================== */
    public function find($id)
    {
        $sql = "
            SELECT *
            FROM t_clientes
            WHERE id_cliente = :id
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================
       ACTUALIZAR CLIENTE
    ========================== */
    public function update($id, $data)
    {
        $sql = "
            UPDATE t_clientes SET
                nomb_cliente = :nomb,
                apel_cliente = :apel,
                cedu_cliente = :cedu,
                celu_cliente = :celu,
                ciud_cliente = :ciud,
                dire_cliente = :dire
            WHERE id_cliente = :id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':nomb' => $data['nomb_cliente'],
            ':apel' => $data['apel_cliente'],
            ':cedu' => $data['cedu_cliente'],
            ':celu' => $data['celu_cliente'],
            ':ciud' => $data['ciud_cliente'],
            ':dire' => $data['dire_cliente'],
            ':id'   => (int)$id
        ]);
    }

    /* =========================
       ELIMINAR CLIENTE (LÓGICO)
    ========================== */
    public function delete($id)
    {
        $sql = "
            UPDATE t_clientes
            SET esta_cliente = 0
            WHERE id_cliente = :id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
