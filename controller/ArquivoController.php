<?php

require_once __DIR__ . '/../config/Banco.php'; 
require_once __DIR__ . '/../model/Arquivo.php';

class ArquivoController
{
    private $conn;
    private const UPLOAD_DIR = __DIR__ . '/../files/'; 

    public function __construct()
    {
        $banco = new Banco();
        $this->conn = $banco->conectar();
    }

    public function uploadESalvar(array $fileData, int $id_entidade = null): Arquivo|bool
    {
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            error_log("Erro no upload: " . $fileData['error']);
            return false;
        }

        $nomeOriginal = $fileData['name'];
        $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
        $hash = hash_file('sha256', $file['tmp_name']);
        $nomeNoServidor = $hash . '.' . $extensao;
        $caminhoAbsoluto = self::UPLOAD_DIR . $nomeNoServidor;
        $caminhoRelativo = 'files/' . $nomeNoServidor;

        try {
            if (!is_dir(self::UPLOAD_DIR)) {
                mkdir(self::UPLOAD_DIR, 0777, true); 
            }

            if (!move_uploaded_file($fileData['tmp_name'], $caminhoAbsoluto)) {
                 throw new Exception("Falha ao mover o arquivo para o disco.");
            }
        } catch (Exception $e) {
            error_log("Erro ao salvar arquivo em disco: " . $e->getMessage());
            return false;
        }

        try {
            $sql = "INSERT INTO tb_arquivo (s_caminho, hash, s_nome_arquivo) 
                    VALUES (:caminho, :hash, :nome_arquivo)
                    RETURNING id_midia";

            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(":caminho", $caminhoRelativo);
            $stmt->bindParam(":hash", $hash);
            $stmt->bindParam(":nome_arquivo", $nomeOriginal);
            
            $stmt->execute();
            
            if (strpos($sql, 'RETURNING') !== false) {
                 $id_midia = $stmt->fetchColumn();
            } else {
                 $id_midia = $this->conn->lastInsertId();
            }
            
            return new Arquivo($id_midia, $caminhoRelativo, $hash, $nomeOriginal);
            
        } catch (PDOException $e) {
            if (file_exists($caminhoAbsoluto)) {
                 unlink($caminhoAbsoluto);
            }
            error_log("Falha no insert do arquivo: " . $e->getMessage());
            return false;
        }
    }

    public function deletar(int $id_midia): bool
    {
        try {
            $sqlSelect = "SELECT s_caminho FROM tb_arquivo WHERE id_midia = :id";
            $stmtSelect = $this->conn->prepare($sqlSelect);
            $stmtSelect->bindParam(":id", $id_midia);
            $stmtSelect->execute();
            $caminhoRelativo = $stmtSelect->fetchColumn();

            if (!$caminhoRelativo) {
                return true;
            }
            
            $caminhoAbsoluto = __DIR__ . '/../' . $caminhoRelativo;

            $sqlDelete = "DELETE FROM tb_arquivo WHERE id_midia = :id";
            $stmtDelete = $this->conn->prepare($sqlDelete);
            $stmtDelete->bindParam(":id", $id_midia);
            $stmtDelete->execute();

            if (file_exists($caminhoAbsoluto)) {
                return unlink($caminhoAbsoluto);
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Falha ao deletar arquivo: " . $e->getMessage());
            return false;
        }
    }
}