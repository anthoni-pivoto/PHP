<?php

class Alternativa {
    public $id_alternativa;
    public $id_pergunta;
    public $ds_alternativa;
    public $correta;

    function __construct($ds_alternativa, $correta = false, $id_pergunta = null, $id_alternativa = null) {
        $this->ds_alternativa = $ds_alternativa;
        $this->correta = $correta;
        $this->id_pergunta = $id_pergunta;
        $this->id_alternativa = $id_alternativa;
    }
}