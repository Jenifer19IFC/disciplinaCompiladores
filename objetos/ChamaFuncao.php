<?php
require_once('Bloco.php');
class ChamaFuncao extends Bloco{

        public $nome;
        public $listaParametros = array();

        function __construct()
        {
                
        }

        function getNome(){
                return $this->nome;
        }
        function setNome($nome){
                $this->nome = $nome;
        }               
    
        function getListaParametros(){
                return $this->listaParametros;
        }
        function setListaParametros($listaParametros){
                $this->listaParametros = $listaParametros;
        }




        public $listVarChamaFuncao = array();
        //objetos para cada não terminal = IF(ATRIBUICAO, ESPRESSAo(objetos) terminal como atributo)

}


?>