<?php

    class Token{

        public $token;
        public $lexema;
        public $posicao;
        public $linha;

        function __construct($token,$lexema,$posicao,$linha){
            $this->token = $token;
            $this->lexema = $lexema;
            $this->posicao = $posicao;
            $this->linha = $linha;
        }

        function mostraToken(){
            return '<b>Token:</b> '.$this->token. ' - <b>Lexema:</b> '.$this->lexema. ' - <b>Posição:</b> '. $this->posicao. ' - <b>Linha:</b> '.$this->linha;
        }
        
    }

?>