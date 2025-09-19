<?php
require_once __DIR__ . '/../config/Banco.php';
require_once __DIR__ . '/../model/Curso.php';

class CursoController {

    private $conn;

    public function __construct() {
        $banco = new Banco();
        $this->conn = $banco->conectar();
    }

    public function criarCurso(Curso $curso) {
        try {
            $sql = "INSERT INTO tb_curso (id_curso, s_nm_curso, s_descricao_curso, id_usuario) 
                    VALUES (nextval('tb_curso_id_curso_seq'), :nm_curso, :ds_curso, :id_usuario)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":nm_curso", $curso->nm_curso);
            $stmt->bindValue(":ds_curso", $curso->descricao_curso);
            $stmt->bindValue(":id_usuario", $curso->id_usuario);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo json_encode(["erro" => "Falha ao criar curso: " . $e->getMessage()]);
            return false;
        }
    }

    public function listarCursos() {
        try {
            $sql = "SELECT * FROM tb_curso ORDER BY id_curso";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(["erro" => "Falha ao listar cursos: " . $e->getMessage()]);
            return [];
        }
    }

    public function buscarCurso($id_curso) {
        try {
            $sql = "SELECT * FROM tb_curso WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id_curso", $id_curso, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(["erro" => "Falha ao buscar curso: " . $e->getMessage()]);
            return null;
        }
    }

    public function atualizarCurso(Curso $curso) {
        try {
            $sql = "UPDATE tb_curso 
                       SET nm_curso = :nm_curso, 
                           ds_curso = :ds_curso 
                     WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":nm_curso", $curso->nm_curso);
            $stmt->bindValue(":ds_curso", $curso->descricao_curso);
            $stmt->bindValue(":id_curso", $curso->id_curso, PDO::PARAM_INT);
            $stmt->execute();
            // Retorna true apenas se realmente atualizou algo
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo json_encode(["erro" => "Falha ao atualizar curso: " . $e->getMessage()]);
            return false;
        }
    }

    public function deletarCurso($id_curso) {
        try {
            $sql = "DELETE FROM tb_curso WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id_curso", $id_curso, PDO::PARAM_INT);
            $stmt->execute();
            // Retorna true apenas se realmente deletou algo
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo json_encode(["erro" => "Falha ao deletar curso: " . $e->getMessage()]);
            return false;
        }
    }
}