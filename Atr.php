<?php

class Atr extends Bloco{

    public $variavelEsq;
    public $variavelDir;  
    public $operador;  
    public $variavelQueRecebe;

    public $operacao;

    function __construct(/*Varr $variavelDir, Varr $variavelEsq, Operacao $operacao*/)
    {
        /*$this->variavelEsq = $variavelEsq;
        $this->variavelDir = $variavelDir;
        $this->operacao = $operacao;*/
    }

    function getVariavelEsq(){
        return $this->variavelEsq;
    }
    function setVariavelEsq(Varr $variavelEsq){
            $this->variavelEsq = $variavelEsq;
    }   
    
    function getVariavelDir(){
        return $this->variavelDir;
    }
    function setVariavelDir(Varr $variavelDir){
            $this->variavelDir = $variavelDir;
    }    

    function getOperador(){
        return $this->operador;
    }
    function setOperador($operador){
            $this->operador = $operador;
    }  
    
    function getVariavelQueRecebe(){
        return $this->variavelQueRecebe;
    }
    function setVariavelQueRecebe($variavelQueRecebe){
            $this->variavelQueRecebe = $variavelQueRecebe;
    }  


}
?>