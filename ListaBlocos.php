<?php


class ListaBlocos{

    //listas não precisam de blocos
    public $bloco;
    public $listaBlocos = array();

    function __construct()
    {
        $this->bloco = new Bloco();
        $this->listaBlocos = new ListaBlocos();
    }

}
?>