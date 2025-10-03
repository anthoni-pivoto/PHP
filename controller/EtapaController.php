<?php
require_once __DIR__ . '/../config/Banco.php';
require_once __DIR__ . '/../model/Etapa.php';

class EtapaController
{
    private $conn;

    public function __construct()
    {
        $banco = new Banco();
        $this->conn = $banco->conectar();
    }

    public function listar()
    {
        $sql = "SELECT * FROM tb_etapa";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function criar($etapa)
    {
        try {
            $sql = "INSERT INTO tb_etapa (id_curso, id_tipo_etapa) 
                VALUES ( :id_curso, :id_tipo_etapa)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_curso", $etapa->id_curso);
            $stmt->bindParam(":id_tipo_etapa", $etapa->id_tipo_etapa);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Retorna o erro em JSON
            echo json_encode(["erro" => "Falha no insert: " . $e->getMessage()]);
            return false;
        }
    }

    public function atualizar($etapa)
    {
        try {
            $sql = "UPDATE tb_etapa 
                       SET id_tipo_etapa = :tipo, 
                           id_questionario = :quest,
                           id_midia = :midia,                            
                     WHERE id_etapa = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":tipo", $etapa->id_tipo_etapa);
            $stmt->bindParam(":quest", $etapa->id_questionario);
            $stmt->bindParam(":midia", $etapa->id_midia);
            $stmt->bindParam(":id", $etapa->id_etapa, PDO::PARAM_INT);

            $stmt->execute();
            // Retornar true apenas se realmente atualizou algo
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo json_encode(["erro" => "Falha no update: " . $e->getMessage()]);
            return false;
        }
    }
    
    public function deletar($id)
    {
        $sql = "DELETE FROM tb_etapa WHERE id_etapa = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }



}