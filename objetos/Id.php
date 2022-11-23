<?php
require_once('Var.php');
class Id extends Varr{

    public $id;

    function __construct($id)
    {
        $this->id = $id;    
    }

    function getId(){
        return $this->id;
    }
    function setId($id){
            $this->id = $id;
    }


}
?>