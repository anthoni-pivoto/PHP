<?php
require_once __DIR__ . '/../config/Banco.php';
// Os models não precisam ser incluídos aqui, pois não serão instanciados diretamente no controller

class QuestionarioController {

    private $conn;

    public function __construct() {
        $banco = new Banco(); // Usando a mesma classe de conexão do seu exemplo
        $this->conn = $banco->conectar();
    }

    /**
     * Cria um questionário completo (com perguntas e respostas) usando uma transação.
     * Recebe o objeto de dados diretamente da API.
     */
    public function criarQuestionarioCompleto($dados) {
        try {
            // 1. Inicia a transação
            $this->conn->beginTransaction();

            // 2. Insere na tb_questionario
            $sqlQuestionario = "INSERT INTO tb_questionario (id_questionario, s_descricao) 
                                VALUES (nextval('tb_questionario_id_questionario_seq'), :descricao)
                                RETURNING id_questionario";
            $stmtQuestionario = $this->conn->prepare($sqlQuestionario);
            $stmtQuestionario->bindValue(":descricao", $dados->descricao);
            $stmtQuestionario->execute();
            $id_questionario = $stmtQuestionario->fetchColumn();

            // 3. Itera sobre as perguntas para inserir na tb_pergunta
            foreach ($dados->perguntas as $perguntaData) {
                $sqlPergunta = "INSERT INTO tb_pergunta (id_pergunta, id_questionario, s_texto_pergunta)
                                VALUES (nextval('tb_pergunta_id_pergunta_seq'), :id_questionario, :texto_pergunta)
                                RETURNING id_pergunta";
                $stmtPergunta = $this->conn->prepare($sqlPergunta);
                $stmtPergunta->bindValue(":id_questionario", $id_questionario);
                $stmtPergunta->bindValue(":texto_pergunta", $perguntaData->texto);
                $stmtPergunta->execute();
                $id_pergunta = $stmtPergunta->fetchColumn();

                // 4. Itera sobre as respostas para inserir na tb_resposta
                foreach ($perguntaData->respostas as $respostaData) {
                    $sqlResposta = "INSERT INTO tb_resposta (id_resposta, id_pergunta, s_texto_resposta, b_correta)
                                    VALUES (nextval('tb_resposta_id_resposta_seq'), :id_pergunta, :texto_resposta, :b_correta)";
                    $stmtResposta = $this->conn->prepare($sqlResposta);
                    $stmtResposta->bindValue(":id_pergunta", $id_pergunta);
                    $stmtResposta->bindValue(":texto_resposta", $respostaData->texto);
                    $stmtResposta->bindValue(":b_correta", $respostaData->correta, PDO::PARAM_BOOL);
                    $stmtResposta->execute();
                }
            }
            
            // 5. Se tudo deu certo, efetiva a transação
            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            // 6. Se algo deu errado, desfaz a transação
            $this->conn->rollBack();
            // Para debug, você pode querer registrar o erro: error_log($e->getMessage());
            return false;
        }
    }

    // Você pode adicionar os outros métodos aqui (listar, buscar, deletar...)
    public function listarQuestionarios() {
        // Implementar a lógica para buscar todos os questionários
    }
}