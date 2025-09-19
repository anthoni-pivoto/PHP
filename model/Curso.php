<?php
class Curso {
    public $id_curso;
    public $id_usuario;
    public $nm_curso;
    public $descricao_curso;

    function __construct($id_usuario, $nm_curso, $descricao_curso, $id_curso = null) {
        $this->id_usuario = $id_usuario;
        $this->nm_curso = $nm_curso;
        $this->descricao_curso = $descricao_curso;
    }

    
}