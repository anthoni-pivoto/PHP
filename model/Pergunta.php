<?php

class Pergunta {
    public $id_pergunta;
    public $id_questionario;
    public $ds_pergunta;
    public $alternativas = []; // array de Alternativa

    function __construct($ds_pergunta, $id_questionario = null, $id_pergunta = null) {
        $this->ds_pergunta = $ds_pergunta;
        $this->id_questionario = $id_questionario;
        $this->id_pergunta = $id_pergunta;
    }
}