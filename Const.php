<?php
require_once('Bloco.php');
class Constt extends Varr{

    public $const;

    function __construct($const)
    {
        $this->const = $const;
    }

    function getConst(){
        return $this->const;
    }
    function setConst(Constt $const){
            $this->const = $const;
    }

}

?>