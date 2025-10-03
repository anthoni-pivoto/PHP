<?php
class Etapa {
    public $id_etapa;
    public $id_curso;
    public $id_tipo_etapa;
    public $id_questionario;
    public $id_midia;


    public function __construct( $id_etapa = null, $id_curso = null, $id_tipo_etapa = null, $id_questionario = null, $id_midia = null) {
       $this->id_etapa = $id_etapa;
       $this->id_curso = $id_curso;
       $this->id_tipo_etapa   = $id_tipo_etapa;
       $this->id_questionario = $id_questionario;
       $this->id_midia = $id_midia;
    }
}

