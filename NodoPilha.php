<?php

class NodoPilha{

    private $dado;
    private $prox;

    function __construct($dado){
		$this->dado = $dado;
		$this->prox = null; //Inicializa com nulo -- na criação não tem ligação
	}

    function getDado(){
        return $this->dado;
    }

    function getProx(){
        return $this->prox;
    }

    function setProx($prox){
        $this->prox = $prox;
    }

}


?>