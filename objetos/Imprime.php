<?php
require_once('Bloco.php');
class Imprime extends Bloco{

    public $varr;

    function __construct()
    {
       
    }

    function getVarr(){
        return $this->varr;
    }
    function setVarr(Varr $varr){
            $this->varr = $varr;
    }


}


?>