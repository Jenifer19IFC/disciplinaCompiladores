<?php

    class Variavel{

       public $tipo;
       public $nome;

        function __construct($nome, $tipo)
        {
            $this->tipo = $tipo;
            $this->nome = $nome;
        }

        function getNome(){
            return $this->nome;
        }
        function setNome($nome){
                $this->nome = $nome;
        }

        function getTipo(){
            return $this->tipo;
        }
        function setTipo($tipo){
                $this->tipo = $tipo;
        }
      
       
        

    }


?>