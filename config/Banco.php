<?php
class Banco {
    private $host = "192.168.20.18"; 
    private $db   = "anthonimigliavasca";  
    private $user = "anthonimigliavasca"; 
    private $port = "5432";
    private $pass = "123456";
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db}";
            $this->conn = new PDO($dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo json_encode(["mensagem" => "Conexão bem-sucedida."]);
        } catch(PDOException $e) {
            echo json_encode(["erro" => "Falha na conexão: " . $e->getMessage()]);
        }
        return $this->conn;
    }
}
                