<?php
require_once('../objetos./Variavel.php');
require_once('../objetos./ChamaFuncao.php');
require_once('../objetos./Param.php');

class ArvoreDerivacao{

    public $programa;
    public $imprime;
    public $encontraNomeFuncao = false;

    //Variáveis IF
    public $iniciaIf = false;
    public $fimIf = false;
    public $encontreiOperadorDir = false;
    public $encontreiOperadorEsq = false;
    public $bloco;
    public $if;
    public $acheiEsq = false;

    public $guardaIfOrWhile = "";
    //Variáveis While
    public $iniciaWhile;
    public $fimWhile;   

    //Variaveis IMPRIME
    public $iniciaPrint = false;
    public $fimPrint = false;


    //Variávei de Fun
    public $chamaFuncao;
    public $iniciaFun = false;
    public $fimFun = false;
    public $expressao;
    public $encontreiNomeChamaFun;
    //public $encontreiNomeChamaFun = false;

    public $variavelDeclarada;
    public $cont = 0;

    //Variáveis Param
    public $param;

    //Variáveis de Atr
    public $iniciaAtr;
    public $fimAtr;
    public $atr;
    public $operadorDir = false;
    public $operadorEsq = false;
    public $variavelQueRecebe;
    public $iniciaExpressao = false;
    public $fimExpressao = false;



    function __construct(){
        
    }

    function arvoreDerivacao(Token $tokenAtualbject, $listVariaveisDeclaradas,$listVariaveisUsadas, $tokenAnterior){

        //Cria objeto Programa a partir do first
        if($tokenAnterior->token == "FUNCTION"){
            $this->programa = new Programa();
        }
        //Seta nome do Programa
        if($tokenAtualbject->token == "ID" && $this->encontraNomeFuncao == false){
            $this->programa->setNome($tokenAtualbject->lexema);
            $this->encontraNomeFuncao = true;
        }
        
        //Seta lista de variáveis no Programa
        foreach($listVariaveisDeclaradas as $key=>$value){
            if(count($this->programa->listaVariaveis) == 0){
                $this->variavelDeclarada = new Variavel($key,$value);
                array_push($this->programa->listaVariaveis,$this->variavelDeclarada);
            }else{
                $bExist = false;
                for($i=0;$i < count($this->programa->listaVariaveis); $i++){
                    if($this->programa->listaVariaveis[$i]->getNome() == $key){
                        $bExist = true;
                    }
                }
                if(!$bExist){
                    $this->variavelDeclarada = new Variavel($key,$value);
                    array_push($this->programa->listaVariaveis,$this->variavelDeclarada);
                }
            }
        }
    
        //--------------------------------- IF -----------------------------------
        
        //Cria bloco IF com ou sem bloco dentro e seta no Programa
        if($tokenAtualbject->token == "IF" && $this->iniciaIf == false && $this->fimIf == false){
            $this->iniciaIf = true;
            $this->guardaIfOrWhile = "IF";
        }
        if($tokenAtualbject->token == "WHILE" && $this->iniciaIf == false && $this->fimIf == false){
            $this->iniciaIf = true;
            $this->guardaIfOrWhile = "WHILE";
        }
        if($tokenAtualbject->token == "ID" && $this->iniciaIf == true && $this->fimIf == false && $this->encontreiOperadorEsq == false){
            $this->encontreiOperadorEsq = true;
            $this->iniciaExpressao = true;
            $varr = new Id($tokenAtualbject->lexema);
            $this->expressao = new Expressao();
            $this->expressao->setOperadorEsquerda($varr);
        }
        if($tokenAtualbject->token == "CONST" && $this->iniciaIf == true && $this->fimIf == false && $this->encontreiOperadorEsq == true && $this->fimExpressao == false){
            $this->encontreiOperadorDir = true;
            $varr = new Constt($tokenAtualbject->lexema);
            $this->expressao->setOperadorDireita($varr);
        }else if($tokenAtualbject->token == "ID" && $this->iniciaIf == true && $this->fimIf == false && $this->encontreiOperadorEsq == true && $this->fimExpressao == false){
           $this->encontreiOperadorDir = true;
           $varr2 = new Id($tokenAtualbject->lexema);
           $this->expressao->setOperadorDireita($varr2);
        }
        
        if($tokenAtualbject->token == "FP" && $this->encontreiOperadorEsq == true && $this->encontreiOperadorDir == true){
            $this->fimExpressao = true;
            if($this->guardaIfOrWhile == "IF"){
                $this->if = new Iff();
                $this->if->setExpressao($this->expressao);
            }else if($this->guardaIfOrWhile == "WHILE"){
                $this->while = new Whilee();
                $this->while->setExpressao($this->expressao);
            }
            
        }
        if($this->fimExpressao == true){
           
            // ------------------------- print no If ------------------------------------
            if($tokenAtualbject->lexema == "print"){
                $this->iniciaPrint = true;
                $this->bloco = new Imprime();   
            }
            if($tokenAtualbject->token == "ID" && $this->iniciaPrint == true && $this->fimPrint == false){
                $varr = new Id($tokenAtualbject->lexema);
                $this->imprime = new Imprime();
                $this->imprime->setVarr($varr);
                if($this->guardaIfOrWhile == "IF"){
                    $this->if->setBloco($this->imprime);
                    if($this->if != null){
                        array_push($this->programa->listaBlocos,$this->if);
                    }
                   
                }else if($this->guardaIfOrWhile == "WHILE"){
                    $this->while->setBloco($this->imprime);
                    if($this->while != null){
                        array_push($this->programa->listaBlocos,$this->while);
                    }
                    
                }
                
            } 
            if($tokenAtualbject->token == "CONST" && $this->iniciaPrint == true && $this->fimPrint == false){
                $varr = new Constt($tokenAtualbject->lexema);
                $this->imprime = new Imprime();
                $this->imprime->setVarr($varr);
                if($this->guardaIfOrWhile == "IF"){
                    $this->if->setBloco($this->imprime);
                    if($this->if != null){
                        array_push($this->programa->listaBlocos,$this->if);
                    }
                    
                }else if($this->guardaIfOrWhile == "WHILE"){
                    $this->while->setBloco($this->imprime);
                    if($this->while != null){
                        array_push($this->programa->listaBlocos,$this->while);
                    }
                   
                }
            }
            if($tokenAtualbject->token == "FC" && $this->iniciaPrint == true ){
                $this->fimPrint = true;
            }

            //------------------------------ chama função no If ----------------------------

            if($tokenAtualbject->lexema == "fun" && $this->iniciaFun == false && $this->iniciaIf == true){
                $this->iniciaFun = true;
            }
            if($tokenAtualbject->token == "ID" && $this->iniciaFun == true && $this->encontreiNomeChamaFun == false){
                $this->chamaFuncao = new ChamaFuncao();
                $this->chamaFuncao->setNome($tokenAtualbject->lexema);
                $this->encontreiNomeChamaFun = true;
            }
    
            if($tokenAtualbject->token == "ID" && $this->iniciaFun == true && $this->encontreiNomeChamaFun == true){
                if(!($tokenAtualbject->lexema == $this->chamaFuncao->getNome())){
                    $id = new Id($tokenAtualbject->lexema);
                    $this->param = new Param();
                    $this->param->setId($id);
                    array_push($this->chamaFuncao->listaParametros,$this->param);
                }
            }
    
            if($tokenAtualbject->token == "FC" && $this->fimFun == false && $this->iniciaFun == true){
                $this->fimFun = true;
                if($this->guardaIfOrWhile == "IF"){
                    $this->if->setBloco($this->chamaFuncao);
                    if($this->if != null){
                        array_push($this->programa->listaBlocos,$this->if);
                    }
                    
                }else if($this->guardaIfOrWhile == "WHILE"){
                    $this->while->setBloco($this->chamaFuncao);
                    if($this->while != null){
                        array_push($this->programa->listaBlocos,$this->while);
                    }
                    
                }
                
            }
            //--------------------------- atribuição no If -----------------------------

            if($tokenAtualbject->token == "ATRIBUICAO" && $this->iniciaIf == true && $this->fimIf == false && $this->iniciaAtr == false){
                $this->iniciaAtr = true;
                $varr = new Id($tokenAnterior->lexema);
                $this->atr = new Atr();
                $this->atr->setVariavelQueRecebe($varr);
            }
            if($tokenAtualbject->token == "ID" && $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == false && $this->iniciaIf == true){
                $this->operadorEsq = true;
                $id = new Id($tokenAtualbject->lexema);
                $this->atr->setVariavelEsq($id);
            }
            if($tokenAtualbject->token == "CONST" && $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == false && $this->iniciaIf == true){
                $this->operadorEsq = true;
                $const = new Constt($tokenAtualbject->lexema);
                $this->atr->setVariavelEsq($const);
            }
            if($tokenAtualbject->token == "FC" && $this->fimAtr == false && $this->iniciaIf == true && $this->operadorEsq = true && $this->iniciaPrint == false && $this->iniciaFun == false){
                $this->fimAtr = true;
                if($this->guardaIfOrWhile == "IF"){
                    $this->if->setBloco($this->atr);
                    if($this->if != null){
                        array_push($this->programa->listaBlocos,$this->if);
                    }
                    
                }else if($this->guardaIfOrWhile == "WHILE"){
                    $this->while->setBloco($this->atr);
                    if($this->while != null){
                        array_push($this->programa->listaBlocos,$this->while);
                    }
                    
                }
                
            }
            
        }//fimExpressao == true
        


        //------------------------ PRINT SOZINHO ---------------------------------------
        
         //Cria bloco Print e seta bloco no Programa
         if($tokenAtualbject->lexema == "print" && $this->iniciaIf == false){
            $this->iniciaPrint = true;
        }
        if($tokenAtualbject->token == "ID" && $this->iniciaPrint == true && $this->fimPrint == false && $this->iniciaIf == false){
            $varr = new Id($tokenAtualbject->lexema);
            $this->imprime = new Imprime();
            $this->imprime->setVarr($varr);
            if($this->imprime != null){
                array_push($this->programa->listaBlocos,$this->imprime);
            }
          
        } 
        if($tokenAtualbject->token == "CONST" && $this->iniciaPrint == true && $this->fimPrint == false && $this->iniciaIf == false){
            $varr = new Constt($tokenAtualbject->lexema);
            $this->imprime = new Imprime();
            $this->imprime->setVarr($varr);
            if($this->imprime != null){
                array_push($this->programa->listaBlocos,$this->imprime);
            }
           
        }
        if($tokenAtualbject->token == "FC" && $this->iniciaPrint == true && $this->iniciaIf == false){
            $this->fimPrint = true;
        }

        // ------------------------- CHAMA FUNÇÃO SOZINHO ---------------------------------

         //Cria objeto  a partir de chamaFuncao
        if($tokenAtualbject->lexema == "fun" && $this->iniciaFun == false && $this->iniciaIf == false){
            $this->iniciaFun = true;
        }
        if($tokenAtualbject->token == "ID" && $this->iniciaFun == true && $this->encontreiNomeChamaFun == false  && $this->iniciaIf == false){
            $this->chamaFuncao = new ChamaFuncao();
            $this->chamaFuncao->setNome($tokenAtualbject->lexema);
            $this->encontreiNomeChamaFun = true;
        }

        if($tokenAtualbject->token == "ID" && $this->iniciaFun == true && $this->encontreiNomeChamaFun == true  && $this->iniciaIf == false){
            if(!($tokenAtualbject->lexema == $this->chamaFuncao->getNome())){
                $id = new Id($tokenAtualbject->lexema);
                $this->param = new Param();
                $this->param->setId($id);
                array_push($this->chamaFuncao->listaParametros,$this->param);
            }
        }

        if($tokenAtualbject->token == "FC" && $this->fimFun == false && $this->iniciaIf == false){
            $this->fimFun = true;
            if($this->chamaFuncao != null){
                array_push($this->programa->listaBlocos,$this->chamaFuncao);
            }
            
        }

        //--------------------------- ATRIBUICAO SOZINHO ---------------------------------


        //Cria Bloco Atribuição e seta no Programa
        if($tokenAtualbject->token == "ATRIBUICAO" && $this->iniciaAtr == false  && $this->iniciaIf == false){
            $this->iniciaAtr = true;
            $varr = new Id($tokenAnterior->lexema);
            $this->atr = new Atr();
            $this->atr->setVariavelQueRecebe($varr);
        }

        if($tokenAtualbject->token == "ID" && $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == false && $this->iniciaIf == false){
            $this->operadorEsq = true;
            $id = new Id($tokenAtualbject->lexema);
            $this->atr->setVariavelEsq($id);
        }
        if($tokenAtualbject->token == "CONST" && $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == false && $this->iniciaIf == false){
            $this->operadorEsq = true;
            $const = new Constt($tokenAtualbject->lexema);
            $this->atr->setVariavelEsq($const);
        }
        if($tokenAtualbject->token == "ID" && $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == true && $this->iniciaIf == false){
            $this->operadorDir = true;
            $id = new Id($tokenAtualbject->lexema);
            $this->atr->setVariavelDir($id);
        }
        if($tokenAtualbject->token == "CONST" && $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == true && $this->iniciaIf == false){
            $this->operadorDir = true;
            $const = new Constt($tokenAtualbject->lexema);
            $this->atr->setVariavelDir($const);
        }
        if(($tokenAtualbject->token == "MAIS" | $tokenAtualbject->token == "MENOS" | $tokenAtualbject->token == "DIVIDE" | $tokenAtualbject->token == "MULT" )&& $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == true && $this->operadorDir == true && $this->iniciaIf == false){
            $this->atr->setOperador($tokenAtualbject->token);
        }

        if($tokenAtualbject->token == "FC" && $this->fimAtr == false && $this->iniciaIf == false){
            $this->fimAtr = true;
            if($this->atr != null){
                array_push($this->programa->listaBlocos,$this->atr);
            }
           
        }


       return $this->programa;





            
    }//funcao arvore
}


?>