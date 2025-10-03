<?php
require_once __DIR__ . '/../config/Banco.php';
require_once __DIR__ . '/../model/Questionario.php';
require_once __DIR__ . '/../model/Pergunta.php';
require_once __DIR__ . '/../model/Alternativa.php';

class QuestionarioController {
    private $conn;

    public function __construct() {
        $banco = new Banco();
        $this->conn = $banco->conectar();
    }

    public function criarQuestionario(Questionario $questionario) {
        try {
            $this->conn->beginTransaction();

            // Inserir questionário
            $sql = "INSERT INTO tb_questionario (id_curso, nm_questionario) 
                    VALUES (:id_curso, :nm_questionario) RETURNING id_questionario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id_curso", $questionario->id_curso);
            $stmt->bindValue(":nm_questionario", $questionario->nm_questionario);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || !isset($row['id_questionario'])) {
                throw new Exception("Erro ao obter ID do questionário.");
            }
            $id_questionario = $row['id_questionario'];

            // Verifica limite de perguntas
            if (count($questionario->perguntas) > 10) {
                throw new Exception("Máximo de 10 perguntas permitido.");
            }

            // Inserir perguntas e alternativas
            foreach ($questionario->perguntas as $pergunta) {
                $sqlPergunta = "INSERT INTO tb_pergunta (id_questionario, ds_pergunta) 
                                VALUES (:id_questionario, :ds_pergunta) RETURNING id_pergunta";
                $stmtPerg = $this->conn->prepare($sqlPergunta);
                $stmtPerg->bindValue(":id_questionario", $id_questionario);
                $stmtPerg->bindValue(":ds_pergunta", $pergunta->ds_pergunta);
                $stmtPerg->execute();

                $rowPerg = $stmtPerg->fetch(PDO::FETCH_ASSOC);
                if (!$rowPerg || !isset($rowPerg['id_pergunta'])) {
                    throw new Exception("Erro ao obter ID da pergunta.");
                }
                $id_pergunta = $rowPerg['id_pergunta'];

                // Verifica se tem 5 alternativas
                if (count($pergunta->alternativas) != 5) {
                    throw new Exception("Cada pergunta deve conter exatamente 5 alternativas.");
                }

                $temCorreta = false;
                foreach ($pergunta->alternativas as $alternativa) {
                    if ($alternativa->correta) {
                        $temCorreta = true;
                    }

                    $sqlAlt = "INSERT INTO tb_alternativa (id_pergunta, ds_alternativa, correta) 
                               VALUES (:id_pergunta, :ds_alternativa, :correta)";
                    $stmtAlt = $this->conn->prepare($sqlAlt);
                    $stmtAlt->bindValue(":id_pergunta", $id_pergunta);
                    $stmtAlt->bindValue(":ds_alternativa", $alternativa->ds_alternativa);
                    $stmtAlt->bindValue(":correta", $alternativa->correta, PDO::PARAM_BOOL);
                    $stmtAlt->execute();
                }

                if (!$temCorreta) {
                    throw new Exception("Cada pergunta deve ter pelo menos uma alternativa correta.");
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            echo json_encode(["erro" => "Falha ao criar questionário: " . $e->getMessage()]);
            return false;
        }
    }

    public function listarQuestionarios() {
        try {
            $sql = "SELECT * FROM tb_questionario ORDER BY id_questionario";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(["erro" => "Falha ao listar questionários: " . $e->getMessage()]);
            return [];
        }
    }
}