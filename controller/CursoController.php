<?php
require_once "Banco.php";
require_once "Curso.php";

class CursoController {

    private $conexao;

    public function __construct() {
        $this->conexao = Banco::getConexao();
    }

    public function criarCurso(Curso $curso) {
        $sql = "INSERT INTO tb_curso (id_curso, nm_curso, ds_curso, id_usuario) 
                VALUES (nextval('seq_curso'), :nm_curso, :ds_curso, :id_usuario)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":nm_curso", $curso->getNmCurso());
        $stmt->bindValue(":ds_curso", $curso->getDsCurso());
        $stmt->bindValue(":id_usuario", $curso->getIdUsuario());
        return $stmt->execute();
    }

    public function listarCursos() {
        $sql = "SELECT * FROM tb_curso ORDER BY id_curso";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarCurso($id_curso) {
        $sql = "SELECT * FROM tb_curso WHERE id_curso = :id_curso";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id_curso", $id_curso);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarCurso(Curso $curso) {
        $sql = "UPDATE tb_curso 
                   SET nm_curso = :nm_curso, 
                       ds_curso = :ds_curso 
                 WHERE id_curso = :id_curso";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":nm_curso", $curso->getNmCurso());
        $stmt->bindValue(":ds_curso", $curso->getDsCurso());
        $stmt->bindValue(":id_curso", $curso->getIdCurso());
        return $stmt->execute();
    }

    public function deletarCurso($id_curso) {
        $sql = "DELETE FROM tb_curso WHERE id_curso = :id_curso";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id_curso", $id_curso);
        return $stmt->execute();
    }
}