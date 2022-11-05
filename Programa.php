<?php

class Programa{

    public $nome;
    public $listaVariaveis = array();
    public $listaBlocos = array();

    function __construct(/*$nome, $listaVariaveis, $listaBlocos*/)
    {
        //$this->nome = $nome;
        //$this->listaVariaveis  = $listaVariaveis;
        //$this->listaBlocos = $listaBlocos;
    }

    function getNome(){
        return $this->nome;
    }
    function setNome($nome){
            $this->nome = $nome;
    }

    function getListaVariaveis(){
        return $this->listaVariaveis;
    }
    function setListaVariaveis($listaVariaveis){
            $this->listaVariaveis = $listaVariaveis;
    }

    function getListaBlocos(){
        return $this->listaBlocos;
    }
    function setListaBlocos($listaBlocos){
            $this->listaBlocos = $listaBlocos;
    }
}

?>