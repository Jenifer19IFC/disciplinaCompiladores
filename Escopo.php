<?php

    class Escopo{

        public $listVariaveisDeclaradas = array(); //Tabela de símbolos 
        public $listVariaveisUsadas = array();
        public $tabelaSimbolos = array();//pilha de escopos -> aqui coloco a lista de variaveis
        //$listVariaveis[$token->lexema]='String'; IMPORTANTE
        public $listVariaveisObjectsDecl = array();
        public $listVarValoresRecebidos = array();
        

 
    }


?>