<?php
//include('app/config/database.php');
require_once __DIR__ . '/../config/database.php';

class User {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function login($usuario, $password) {

        $sql = "SELECT * FROM t_usuarios 
                WHERE cedu_usuario = :usuario 
                AND esta_usuario = 1 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['pass_usuario'])) {
            return $user;
        }

        return false;
    }
}
