<?php
class Usuario {
    public $id_usuario;
    public $nm_usuario;
    public $email_usuario;
    public $pwd_usuario;
    public $email_contato;
    public $numero_telefone;
    

    public function __construct( $id_usuario = null, $nm_usuario = null, $email_usuario = null, $pwd_usuario = null, $email_contato = null, $numero_telefone = null) {
       $this->id_usuario = $id_usuario;
        $this->nm_usuario   = $nm_usuario;
        $this->email_usuario = $email_usuario;
        $this->pwd_usuario  = $pwd_usuario;
        $this->email_contato  = $email_contato;
        $this->numero_telefone  = $numero_telefone;
    }
}

