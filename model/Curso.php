<?php
class Curso {
    public $id_curso;
    public $id_usuario;
    public $s_nm_curso;
    public $s_descricao_curso;

    function __construct($id_usuario, $s_nm_curso, $s_descricao_curso) {
        $this->id_usuario = $id_usuario;
        $this->s_nm_curso = $s_nm_curso;
        $this->s_descricao_curso = $s_descricao_curso;
    }

    
}