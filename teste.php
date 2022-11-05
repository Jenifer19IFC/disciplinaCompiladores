<?php

 // ----------------------- WHILE ---------------------------------------

            //Cria bloco WHILE com ou sem bloco dentro e seta no Programa
            if($tokenAtualbject->token == "WHILE" && $this->iniciaWhile == false && $this->fimWhile == false){
                $this->iniciaWhile = true;
            }
            if($tokenAtualbject->token == "ID" && $this->iniciaWhile == true && $this->fimWhile == false && $this->encontreiOperadorEsq == false){
                $this->encontreiOperadorEsq = true;
                $this->iniciaExpressao = true;
                $varr = new Id($tokenAtualbject->lexema);
                $this->expressao = new Expressao();
                $this->expressao->setOperadorEsquerda($varr);
            }
            if($tokenAtualbject->token == "CONST" && $this->iniciaWhile == true && $this->fimWhile == false && $this->encontreiOperadorEsq == true && $this->fimExpressao == false){
                $this->encontreiOperadorDir = true;
                $varr = new Constt($tokenAtualbject->lexema);
                $this->expressao->setOperadorDireita($varr);
            }else if($tokenAtualbject->token == "ID" && $this->iniciaWhile == true && $this->fimWhile == false && $this->encontreiOperadorEsq == true && $this->fimExpressao == false){
            $this->encontreiOperadorDir = true;
            $varr2 = new Id($tokenAtualbject->lexema);
            $this->expressao->setOperadorDireita($varr2);
            }

            if($tokenAtualbject->token == "FP" && $this->encontreiOperadorEsq == true && $this->encontreiOperadorDir == true){
                $this->fimExpressao = true;
                $this->while = new Whilee();
                $this->while->setExpressao($this->expressao);
            }
            if($this->fimExpressao == true){
                // -------------------- print no While -----------------------------
                if($tokenAtualbject->lexema == "print"){
                    $this->iniciaPrint = true;
                    $this->bloco = new Imprime();   
                }
                if($tokenAtualbject->token == "ID" && $this->iniciaPrint == true && $this->fimPrint == false){
                    $varr = new Id($tokenAtualbject->lexema);
                    $this->imprime = new Imprime();
                    $this->imprime->setVarr($varr);
                    $this->while->setBloco($this->imprime);
                    array_push($this->programa->listaBlocos,$this->while);
                } 
                if($tokenAtualbject->token == "CONST" && $this->iniciaPrint == true && $this->fimPrint == false){
                    $varr = new Constt($tokenAtualbject->lexema);
                    $this->imprime = new Imprime();
                    $this->imprime->setVarr($varr);
                    $this->while->setBloco($this->imprime);
                    array_push($this->programa->listaBlocos,$this->while);
                }
                if($tokenAtualbject->token == "FC" && $this->iniciaPrint == true ){
                    $this->fimPrint = true;
                }

                //--------------------- chama função no While --------------------------

                if($tokenAtualbject->lexema == "fun" && $this->iniciaFun == false && $this->iniciaWhile == true){
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
                    $this->while->setBloco($this->chamaFuncao);
                    array_push($this->programa->listaBlocos,$this->while);
                }
                //--------------------- atribuição no While --------------

                if($tokenAtualbject->token == "ATRIBUICAO" && $this->iniciaWhile == true && $this->fimWhile == false && $this->iniciaAtr == false){
                    $this->iniciaAtr = true;
                    $varr = new Id($tokenAnterior->lexema);
                    $this->atr = new Atr();
                    $this->atr->setVariavelQueRecebe($varr);
                }
                if($tokenAtualbject->token == "ID" && $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == false && $this->iniciaWhile == true){
                    $this->operadorEsq = true;
                    $id = new Id($tokenAtualbject->lexema);
                    $this->atr->setVariavelEsq($id);
                }
                if($tokenAtualbject->token == "CONST" && $this->iniciaAtr == true && $this->fimAtr == false && $this->operadorEsq == false && $this->iniciaWhile == true){
                    $this->operadorEsq = true;
                    $const = new Constt($tokenAtualbject->lexema);
                    $this->atr->setVariavelEsq($const);
                }
                if($tokenAtualbject->token == "FC" && $this->fimAtr == false && $this->iniciaWhile == true && $this->operadorEsq = true && $this->iniciaPrint == false && $this->iniciaFun == false){
                    $this->fimAtr = true;
                    $this->while->setBloco($this->atr);
                    array_push($this->programa->listaBlocos,$this->while);
                }
            }//fimExpressao == true
?>