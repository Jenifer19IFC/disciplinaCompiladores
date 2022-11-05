<?php
require_once('Var.php');
require_once('Id.php');
require_once('Const.php');

class Expressao{

    public $operadorEsquerda;
    public $operadorDireita;

    function __construct(/*$operadorEsquerda,$operadorDireita*/)
    {
        /*$this->operadorDireita  = $operadorDireita;
        $this->operadorEsquerda = $operadorEsquerda;*/
    }

    function getOperadorEsquerda(){
        return $this->operadorEsquerda;
    }
    function setOperadorEsquerda($operadorEsquerda){
        $this->operadorEsquerda = $operadorEsquerda;
    }

    function getOperadorDireita(){
        return $this->operadorDireita;
    }
    function setOperadorDireita($operadorDireita){
        $this->operadorDireita = $operadorDireita;
    }

    
}
?>