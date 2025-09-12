<?php 
class Usuario {
    public $id_usuario;
    public $nm_usuario;
    public $email_usuario;
    public $pwd_usuario;

    public function __construct( $nm_usuario = null, $email_usuario = null, $pwd_usuario = null) {
        $this->nm_usuario   = $nm_usuario;
        $this->email_usuario = $email_usuario;
        $this->pwd_usuario  = $pwd_usuario;
    }

    // public function setUsuario(){
    //     $this->id_usuario = $id_usuario;
    // }
}