<?php
class Database {

    /*
    private $host = "localhost";
    private $db   = "omodxbev_sisinventario";
    private $user = "omodxbev_sisinventario";
    private $pass = "HUMMVeAG5CdngJkQyDLq";
    private $conn;
    */
    private $host = "localhost";
    private $db   = "sisinventario_db";
    private $user = "root";
    private $pass = "";
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db};charset=utf8",
                $this->user,
                $this->pass
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Error BD: " . $e->getMessage());
        }

        return $this->conn;
    }
}
