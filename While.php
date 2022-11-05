<?php
require_once('Bloco.php');
class Whilee extends Bloco{

    public $expressao;
    public $bloco;

    function __construct(/*Expressao $expressao, Bloco $bloco*/)
    {
        //$this->expressao = $expressao;
        //$this->bloco = $bloco; 
    }

    function getExpressao(){
        return $this->expressao;
    }
    function setExpressao($expressao){
            $this->expressao = $expressao;
    }

    function getBloco(){
        return $this->bloco;
    }
    function setBloco($bloco){
            $this->bloco = $bloco;
    }


}

?>