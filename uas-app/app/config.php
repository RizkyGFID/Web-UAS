<?php
class Config {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "db_uas_proweb";
    protected $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Koneksi gagal: " . $e->getMessage();
        }
    }
}