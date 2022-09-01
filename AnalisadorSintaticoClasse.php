<?php

require_once('AnalisadorLexicoClasse.php');

class Sintatico{

    public $cont;
    protected $lexico;
    protected $token;   
    
    function __construct(Lexico $lexico){
        $this->cont = 0;
        $this->lexico = $lexico;
    }

    function term($token){
        print("<br><br>");
        if($this->cont >= count($this->lexico->lista_tokens)){
            return false;
        }
        $ret =  $this->lexico->lista_tokens[$this->cont]->token == $token;
        $this->cont  = $this->cont + 1;
        return $ret;
    }

   function LISTA_VARIAVEIS01(){
       print('<LISTA_VARIAVEIS> ::= <VARIAVEL>');
        return $this->VARIAVEL();
    }

    function LISTA_VARIAVEIS02(){
        print('<LISTA_VARIAVEIS> ::= <VARIAVEL> <LISTA_VARIAVEIS2> ');
        return $this->VARIAVEL() and $this->LISTA_VARIAVEIS2();
    }

    function LISTA_VARIAVEIS201(){
        print('<LISTA_VARIAVEIS2>::= PV <LISTA_VARIAVEIS>');
        return $this->term('PV') and $this->LISTA_VARIAVEIS();
    }

    function LISTA_VARIAVEIS202(){
        print('<LISTA_VARIAVEIS2>::= Î');
        return true;
    }

    function LISTA_VARIAVEIS2(){
        print('<LISTA_VARIAVEIS> ::= PV <LISTA_VARIAVEIS> | î');
        $this->anterior = $this->cont;
        if($this->LISTA_VARIAVEIS201()){
            return true;
        }else{
            $this->cont = $this->anterior;
            return $this->LISTA_VARIAVEIS202();
        }

    }

    function LISTA_VARIAVEIS(){
        print('<LISTA_VARIAVEIS> ::= <VARIAVEL> <LISTA_VARIAVEIS2>');
        $this->anterior = $this->cont;
        if($this->LISTA_VARIAVEIS01()){
            return true;
        }else{
            $this->cont = $this->anterior;
            return $this->LISTA_VARIAVEIS02();
        }

    }

    function VARIAVEL(){
        print('<VARIAVEL> ::= TIPO ID');
        return $this->term('TIPO') and $this->term('ID'); 
    }

    function LISTA_BLOCOS01(){
        print('<LISTA_BLOCOS>::= <BLOCO>');
        return $this->BLOCO();
    }

    function LISTA_BLOCOS02(){
        print('<LISTA_BLOCOS>::= <BLOCO> <LISTA_BLOCOS>');
        return $this->BLOCO() and $this->LISTA_BLOCOS();
    }

    function LISTA_BLOCOS03(){
        print(' <LISTA_BLOCOS>::= <BLOCO> <LISTA_BLOCOS> |î');
        return true;
    }

    function LISTA_BLOCOS(){
        print('<LISTA_BLOCOS>::= <BLOCO> <LISTA_BLOCOS> |î');
        $this->anterior = $this->cont;
        if($this->LISTA_BLOCOS01()){
            return true;
        }else{
            $this->cont = $this->anterior;
            if($this->LISTA_BLOCOS02()){
                return true;
            }else{
                $this->cont = $this->anterior;
                
                return $this->LISTA_BLOCOS03();
            }
        }
    }

    function P(){
        print('<P>::= FUNCTION ID AP <LISTA_VARIAVEIS> FP AC <LISTA_BLOCOS> FC');
        return $this->term('FUNCTION') and $this->term('ID') and $this->term('AP') and $this->LISTA_VARIAVEIS() and $this->term('FP') and $this->term('AC') and $this->LISTA_BLOCOS() and $this->term('FC');
    }

    //<BLOCO>::= <ATR>|<IF>|<WHILE>|<IMPRIME>|<CHAMA_FUNCAO>;

    function BLOCO01(){
        print('<BLOCO>::= <ATR>');
        return $this->ATR();
    }

    function BLOCO02(){
        print('<BLOCO>::= <IF>');
        return $this->IF();
    }

    function BLOCO03(){
        print('<BLOCO>::= <WHILE>');
        return $this->WHILE();
    }

    function BLOCO04(){
        print('<BLOCO>::= <IMPRIME>');
        return $this->IMPRIME();
    }

    function BLOCO05(){
        print('<BLOCO>::= <CHAMA_FUNCAO>');
        return $this->CHAMA_FUNCAO();
    }

    function BLOCO(){
        print('<BLOCO>::= <ATR>|<IF>|<WHILE>|<IMPRIME>|<CHAMA_FUNCAO>');
        $this->anterior = $this->cont;
        if($this->BLOCO01()){
            return true;
        }
        else{
            $this->cont = $this->anterior;
            if($this->BLOCO02()){
                return true;
            }else{
                $this->cont = $this->anterior;
                if($this->BLOCO03()){
                    return true;
                }else{
                    $this->cont = $this->anterior;
                    if($this->BLOCO04()){
                        return true;
                    }else{
                        $this->cont = $this->anterior;
                        return $this->BLOCO05();
                    }
                }
            }
        }
    }

    function ATR(){
        print('<ATR>::= <VAR> ATRIBUICAO <VAR> <OPERACAO> PV');
        return $this->VAR() and $this->term('ATRIBUICAO') and $this->VAR() and $this->OPERACAO() and $this->term('PV');
    }

    function VAR01(){
        print('<VAR>::=  const');
        return $this->term('const');
    }

    function VAR02(){
        print('<VAR>::=  ID');
        return $this->term('ID');
    }

    function VAR(){
        print('<VAR>::=  const| ID');
        $this->anterior = $this->cont;
        if($this->VAR01()){
            return true;
        }else{
            $this->cont = $this->anterior;
            return $this->VAR02();
        }
    }

    function OPERACAO01(){
        print('<OPERACAO>::= MAIS <VAR>');
        return $this->term('MAIS') and $this->VAR();
    }

    function OPERACAO02(){
        print('<OPERACAO>::= MENOS <VAR>');
        return $this->term('MENOS') and $this->VAR();
    }

    function OPERACAO03(){
        print('<OPERACAO>::= MULT <VAR>');
        return $this->term('MULT') and $this->VAR();
    }

    function OPERACAO04(){
        print('<OPERACAO>::= DIVIDE <VAR>');
        return $this->term('DIVIDE') and $this->VAR();
    }

    function OPERACAO05(){
        print('<OPERACAO>::= î');
        return true;
    }

    function OPERACAO(){
        print('<OPERACAO>::= MAIS <VAR> | MENOS <VAR> | MULT <VAR> | DIVIDE <VAR> |î');
        $this->anterior = $this->cont;
        if($this->OPERACAO01()){
            return true;
        }else{
            $this->cont = $this->anterior;
            if($this->OPERACAO02()){
                return true;
            }else{
                $this->cont = $this->anterior;
                if($this->OPERACAO03()){
                    return true;
                }else{
                    $this->cont = $this->anterior;
                    if($this->OPERACAO04()){
                        return true;
                    }else{
                    $this->cont = $this->anterior;
                        return $this->OPERACAO05();
                }

                }
            }
        }
    }

    function IF(){
        print('<IF>::= IF AP ID COMPARA <VAR> FP AC <BLOCO> FC');
        return $this->term('IF') and $this->term('AP') and $this->term('ID') and $this->term('COMPARA') and $this->VAR() and $this->term('FP') and $this->term('AC') and $this->BLOCO() and $this->term('FC');
    } 

    function WHILE(){
        print('<WHILE>::= WHILE AP ID COMPARA <VAR> FP AC <BLOCO> FC');
        return $this->term('WHILE') and $this->term('AP') and $this->term('ID') and $this->term('COMPARA') and $this->VAR() and $this->term('FP') and $this->term('AC') and $this->BLOCO() and $this->term('FC');
    }

    function IMPRIME(){
        print('<IMPRIME>::= PRINT AP <VAR> FP PV');
        return $this->term('PRINT') and $this->term('AP') and $this->VAR() and $this->term('FP') and $this->term('PV');
    }

    function CHAMA_FUNCAO(){
        print('<CHAMA_FUNCAO>::= FUN PT ID AP <PARAM> FP PV');
        return $this->term('FUN') and $this->term('PT') and $this->term('ID') and $this->term('AP') and $this->PARAM() and $this->term('FP') and $this->term('PV');
    }

    function PARAM01(){
        print('<PARAM>::= CIFRAO ID V <PARAM2>');
        return $this->term('CIFRAO') and $this->term('ID') and $this->term('V') and $this->PARAM();
    }

    function PARAM02(){
        print('<PARAM>::= Î;');
        return true;
    }

    function PARAM(){
        print('<PARAM>::= CIFRAO ID V <PARAM2> | î');
        $this->anterior = $this->cont;
        if($this->PARAM01()){
            return true;
        }else{
            $this->cont = $this->anterior;
            return $this->PARAM02();
        }
    }

   

}


?>