<?php 

class Operacao{

    public $operador;
    public $varr;

    function __construct(Varr $varr,$operador)
    {
        $this->varr = $varr;
        $this->operador = $operador;
    }

    function getVarr(){
        return $this->varr;
    }
    function setVarr($varr){
            $this->varr = $varr;
    }

    function getOperador(){
        return $this->operador;
    }
    function setOperador($operador){
            $this->operador = $operador;
    }

}
?>