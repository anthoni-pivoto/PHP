<?php

class Arquivo
{
    private $id_midia;
    private $s_caminho;
    private $hash;
    private $s_nome_arquivo;

    public function __construct($id_midia, $s_caminho, $hash, $s_nome_arquivo)
    {
        $this->id_midia = $id_midia;
        $this->s_caminho = $s_caminho;
        $this->hash = $hash;
        $this->s_nome_arquivo = $s_nome_arquivo;
    }
}