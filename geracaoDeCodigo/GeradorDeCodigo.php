<?php

class GeradorCodigo{

    
    function geraCodigoAssembly(Programa $programa){
        echo "<br><br>";
        //Verifica qual objeto tem dentro do bloco
        if($programa->listaBlocos[0] instanceof Atr){
            return $this->seInstanceAtr($programa);
        }
        else if($programa->listaBlocos[0] instanceof Iff){
            return $this->seInstanceIff($programa);
        }
        else if($programa->listaBlocos[0] instanceof Imprime){
            return $this->seInstanceImprime($programa);
        }
        echo "<br><br>";

    }

    function seInstanceAtr(Programa $programa){
        //Verifica qual operação
        $operacao = "";
        if($programa->listaBlocos[0]->operador == "MAIS"){
            $operacao = "add";
        }
        else if($programa->listaBlocos[0]->operador == "MENOS"){
            $operacao = "sub";
        }
        else if($programa->listaBlocos[0]->operador == "MULT"){
            $operacao = "mul";
        }
        if($programa->listaBlocos[0]->operador == "DIVIDE"){
            $operacao = "div";
        }

        //Quando valores de Atr são todos inteiros 
        if($programa->listaBlocos[0]->variavelEsq instanceof Constt && $programa->listaBlocos[0]->variavelDir instanceof Constt){
            $assembly =  "<i>lw \$t1, ".$programa->listaBlocos[0]->variavelEsq->const."
                lw \$t2, ".$programa->listaBlocos[0]->variavelDir->const."
                ".$operacao." \$t0,\$t1,\$t2</i>";
            return nl2br($assembly);
        }
        //Quando variávelEsq é variável
        if($programa->listaBlocos[0]->variavelEsq instanceof Id && $programa->listaBlocos[0]->variavelDir instanceof Constt){
            $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->variavelEsq->id.": .word 10\n
            .text\n\n lw \$t1, ".$programa->listaBlocos[0]->variavelEsq->id."
            lw \$t2, ".$programa->listaBlocos[0]->variavelDir->const."
            ".$operacao." \$t0,\$t1,\$t2</i>";
            return nl2br($assembly);
        }
        //Quando variávelDir é variável
        if($programa->listaBlocos[0]->variavelEsq instanceof Constt && $programa->listaBlocos[0]->variavelDir instanceof Id){
            $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->variavelDir->id.": .word 10\n
            .text\n\n lw \$t1, ".$programa->listaBlocos[0]->variavelEsq->const."
            lw \$t2, ".$programa->listaBlocos[0]->variavelDir->id."
            ".$operacao." \$t0,\$t1,\$t2</i>";
            return nl2br($assembly);
        }
        //Quando são todas variáveis
        if($programa->listaBlocos[0]->variavelEsq instanceof Id && $programa->listaBlocos[0]->variavelDir instanceof Id){
            if($programa->listaBlocos[0]->variavelDir->id != $programa->listaBlocos[0]->variavelEsq->id){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->variavelDir->id.": .word 10\n ".$programa->listaBlocos[0]->variavelEsq->id.": .word 7\n 
                .text\n\n lw \$t1, ".$programa->listaBlocos[0]->variavelEsq->id."
                lw \$t2, ".$programa->listaBlocos[0]->variavelDir->id."
                ".$operacao." \$t0,\$t1,\$t2</i>";
            }else{
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->variavelDir->id.": .word 10\n
                .text\n\n lw \$t1, ".$programa->listaBlocos[0]->variavelEsq->id."
                lw \$t2, ".$programa->listaBlocos[0]->variavelDir->id."
                ".$operacao." \$t0,\$t1,\$t2</i>";
            }
            return nl2br($assembly);
        }
    }//seInstanceAtr


    function seInstanceIff(Programa $programa){
        $tipoExpessaoIf = "";
        //Verifica tipo da Expressão
        for ($i=0; $i < count($programa->listaVariaveis); $i++) { 
            if($programa->listaBlocos[0]->expressao->operadorDireita instanceof Id){
                if($programa->listaBlocos[0]->expressao->operadorDireita->id == $programa->listaVariaveis[$i]->nome){
                    $tipoExpessaoIf = $programa->listaVariaveis[$i]->tipo;
                }
            }
        }
    
        //Se Expressao for string
        if($tipoExpessaoIf == "string"){
            //Quando a==a
            if($programa->listaBlocos[0]->expressao->operadorDireita->id == $programa->listaBlocos[0]->expressao->operadorEsquerda->id){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.":  .asciiz \"token\" \n
                .text\n\n la \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                la \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label </i>";
            }
            //Quando a==b
            else if($programa->listaBlocos[0]->expressao->operadorDireita->id != $programa->listaBlocos[0]->expressao->operadorEsquerda->id){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.":  .asciiz \"analisador\" \n". $programa->listaBlocos[0]->expressao->operadorDireita->id .": .asciiz \"arvoreDerivação\"\n
                .text\n
                la \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                la \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label </i>";
            }
        }

        $encontreiAtr = false;
        //Quando a==32, por exemplo
        if($programa->listaBlocos[0]->expressao->operadorEsquerda instanceof Id && $programa->listaBlocos[0]->expressao->operadorDireita instanceof Constt && $tipoExpessaoIf != "string"){
            $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10\n
            .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
            lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->const."
            beq \$t1,\$t2, label </i>";
        }
       
        //Quando a==b, por exemplo
        else if($programa->listaBlocos[0]->expressao->operadorEsquerda instanceof Id && $programa->listaBlocos[0]->expressao->operadorDireita instanceof Id  && $tipoExpessaoIf != "string"){
            //Quando ids são diferentes
            if($programa->listaBlocos[0]->expressao->operadorEsquerda->id != $programa->listaBlocos[0]->expressao->operadorDireita->id ){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10\n".
                $programa->listaBlocos[0]->expressao->operadorDireita->id.": .word 34\n
                .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label </i>";
            }else{
                //Quando ids são iguais
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10\n
                .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label \n\n </i>";
            }     
        }//else if
        
        $label1 = $this->seInstanceIffBlocoAtr($programa);
        $label = $this->seInstanceIffBlocoImprime($programa);
        
        $teste = $this->seInstanceIffBlocoAtr($programa);

        if($teste == null){
            return nl2br($assembly.$label1.$label);
        }else{
            return nl2br($teste);
        }
            
        
    }

    function seInstanceImprime(Programa $programa){
        //Verifica o tipo da variável de impressão
        $tipoImprime = "";
        for ($i=0; $i < count($programa->listaVariaveis); $i++) { 
            if($programa->listaBlocos[0]->varr instanceof Id){
                if($programa->listaBlocos[0]->varr->id == $programa->listaVariaveis[$i]->nome){
                    $tipoImprime = $programa->listaVariaveis[$i]->tipo;
                }
            }
        }
        //Quando é uma variável do tipo int
        if($programa->listaBlocos[0]->varr instanceof Id  && $tipoImprime == "int"){
            $assembly = "<i>.data\n\n".$programa->listaBlocos[0]->varr->id.": .word 10\n\n .text\n
            li \$v0, 1
            lw \$a0, ".$programa->listaBlocos[0]->varr->id. "\n\n syscall";
        }
        //Quando é uma variável do tipo string
        else if($programa->listaBlocos[0]->varr instanceof Id  && $tipoImprime == "string"){
            $assembly = "<i>.data\n\n".$programa->listaBlocos[0]->varr->id.": .asciiz \"Este é meu compilador\"\n\n .text\n
            li \$v0, 4
            la \$a0, ".$programa->listaBlocos[0]->varr->id. "\n\n syscall";
        }
        //Quando é uma constante
        else if($programa->listaBlocos[0]->varr instanceof Constt){
            $assembly = "<i>.data\n\n variavelAux: .word ".$programa->listaBlocos[0]->varr->const."\n\n .text\n
            li \$v0, 1
            lw \$a0, variavelAux \n\n syscall";
        }
        return nl2br($assembly);
    }
    
    function seInstanceIffBlocoImprime(Programa $programa){
        $isAtr = false;
        $label = "";
        //Verifica tipo de Varr dentro do bloco if quando tenho Imprime
        if($programa->listaBlocos[0]->bloco instanceof Imprime){
            $isAtr = true;
            $tipoImprime = "";
            for ($i=0; $i < count($programa->listaVariaveis); $i++) { 
                if($programa->listaBlocos[0]->bloco->varr instanceof Id){
                    if($programa->listaBlocos[0]->bloco->varr->id== $programa->listaVariaveis[$i]->nome){
                        $tipoImprime = $programa->listaVariaveis[$i]->tipo;
                    }
                }
            }
        
        }//instanceof imprime
        //Quando variável é do tipo int
        if(!empty($programa->listaBlocos[0]->bloco->varr)){
            //print a [tipo int]
            if($programa->listaBlocos[0]->bloco->varr instanceof Id  && $tipoImprime == "int" && $isAtr){
                $label = "<i>\n\nlabel:\n
                li \$v0, 1
                lw \$a0, ".$programa->listaBlocos[0]->bloco->varr->id."
                syscall</i>";
            }
          //print a [tipo string]  
            else if($programa->listaBlocos[0]->bloco->varr instanceof Id  && $tipoImprime == "string"){
                $label = "<i>\n\nlabel:\n
                li \$v0, 4
                la \$a0, ".$programa->listaBlocos[0]->bloco->varr->id."
                syscall</i>";
            }
            //print(32)
            else if($programa->listaBlocos[0]->bloco->varr instanceof Constt){
                $label = "<i>\n\nlabel:\n
                li \$v0, 1
                lw \$a0, ".$programa->listaBlocos[0]->bloco->varr->const."
                syscall</i>";
            }
        }

        return $label;
    }//seInstanceIffBlocoImprime

    function seInstanceIffBlocoAtr(Programa $programa){
        $encontreiAtr = false;
        $assembly = "";
        $label = "";
        $tipoAtr = "";
        $tipoExpressao = "";
        if($programa->listaBlocos[0]->bloco instanceof Atr){
            $encontreiAtr = true;
            //Verifica o tipo da atribuição
            for ($i=0; $i < count($programa->listaVariaveis); $i++) { 
                if($programa->listaBlocos[0]->bloco->variavelQueRecebe instanceof Id){
                    if($programa->listaBlocos[0]->bloco->variavelQueRecebe->id == $programa->listaVariaveis[$i]->nome){
                        $tipoAtr = $programa->listaVariaveis[$i]->tipo;
                    }
                }
            }
            //Verifica p tipo da expressão
            for ($i=0; $i < count($programa->listaVariaveis); $i++) { 
                if($programa->listaBlocos[0]->expressao->operadorEsquerda instanceof Id){
                    if($programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaVariaveis[$i]->nome){
                        $tipoExpressao = $programa->listaVariaveis[$i]->tipo;
                    }
                }
            }
        }
        //echo "<b>Expressão</b> do tipo ". $tipoExpressao;
        //echo "<br> <b>Atr</b> do tipo ". $tipoAtr."<br>";

        if($encontreiAtr){
            if($tipoExpressao == "int" && $tipoAtr == "string"){
                //if(a==53){b=b} : primeiro int e segundo string
                if($programa->listaBlocos[0]->expressao->operadorEsquerda instanceof Id && $programa->listaBlocos[0]->expressao->operadorDireita instanceof Constt){
                    $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                    ".$programa->listaBlocos[0]->bloco->variavelEsq->id.": .asciiz \"linha de código\"\n
                    .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                    lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->const."
                    beq \$t1,\$t2, label </i>";
                    $label =  "<i>\n\nlabel:\n
                    lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                    return $assembly.$label; 
                }
            }
            if($tipoAtr == "int"){ 
                //if(a==32){a=324}
                if( $programa->listaBlocos[0]->expressao->operadorDireita instanceof Constt){
                    if($programa->listaBlocos[0]->bloco->variavelEsq instanceof Constt && $programa->listaBlocos[0]->expressao->operadorEsquerda instanceof Id && $programa->listaBlocos[0]->expressao->operadorDireita->const && $programa->listaBlocos[0]->expressao->operadorEsquerda == $programa->listaBlocos[0]->bloco->variavelQueRecebe ){
                        $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10\n
                        .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                        lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->const."
                        beq \$t1,\$t2, label </i>";
                        $label =  "<i>\n\nlabel:\n
                        lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->const."</i>";
                        return $assembly.$label; 
                    }
                     //if(a==32){b=324}
                     //Abaixo iguais. Diferem de: variavelEsq->id e outro variavelEsq->const no final
                     if($programa->listaBlocos[0]->bloco->variavelEsq instanceof Id){
                        if($programa->listaBlocos[0]->expressao->operadorEsquerda->id != $programa->listaBlocos[0]->bloco->variavelQueRecebe->id){
                            $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                            ".$programa->listaBlocos[0]->bloco->variavelQueRecebe->id.": .word 11\n
                            .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                            lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->const."
                            beq \$t1,\$t2, label </i>";
                            $label =  "<i>\n\nlabel:\n
                            lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                            return $assembly.$label; 
                        } 
                     }
                     if($programa->listaBlocos[0]->bloco->variavelEsq instanceof Constt){
                        if($programa->listaBlocos[0]->expressao->operadorEsquerda->id != $programa->listaBlocos[0]->bloco->variavelQueRecebe->id){
                            $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                            ".$programa->listaBlocos[0]->bloco->variavelQueRecebe->id.": .word 11\n
                            .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                            lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->const."
                            beq \$t1,\$t2, label </i>";
                            $label =  "<i>\n\nlabel:\n
                            lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->const."</i>";
                            return $assembly.$label; 
                        } 
                     }
                     
                    if($programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->bloco->variavelQueRecebe->id && $programa->listaBlocos[0]->bloco->variavelQueRecebe->id != $programa->listaBlocos[0]->bloco->variavelEsq->id){
                        $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                        ".$programa->listaBlocos[0]->bloco->variavelEsq->id.": .word 11\n
                        .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                        lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->const."
                        beq \$t1,\$t2, label </i>
                       \n\nlabel:\n
                        lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                        return $assembly.$label; 
                    }  
                }//const
                if($programa->listaBlocos[0]->bloco->variavelEsq instanceof Constt){
                    if($programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelQueRecebe->id != $programa->listaBlocos[0]->expressao->operadorDireita->id ){
                        $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                        ".$programa->listaBlocos[0]->bloco->variavelQueRecebe->id.": .word 0\n
                        .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                        lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                        beq \$t1,\$t2, label \n
                        label:\n
                        lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->const."</i>";
                        return $assembly.$label; 
                    } 
                    else if($programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelQueRecebe->id == $programa->listaBlocos[0]->expressao->operadorDireita->id ){
                        $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10\n
                        .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                        lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                        beq \$t1,\$t2, label \n
                        label:\n
                        lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->const."</i>";
                        return $assembly.$label; 
                    }
                    
                }
                if($programa->listaBlocos[0]->bloco->variavelEsq instanceof Id){
                    if($programa->listaBlocos[0]->bloco->variavelEsq->id == $programa->listaBlocos[0]->bloco->variavelQueRecebe->id && $programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->expressao->operadorEsquerda->id != $programa->listaBlocos[0]->bloco->variavelEsq->id){
                        $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                        ".$programa->listaBlocos[0]->bloco->variavelEsq->id.": .word 3\n
                        .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                        lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                        beq \$t1,\$t2, label \n
                        label:\n
                        lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                        return $assembly.$label; 
                }
                }
                if($programa->listaBlocos[0]->bloco->variavelEsq instanceof Id){
                    if($programa->listaBlocos[0]->bloco->variavelEsq->id == $programa->listaBlocos[0]->bloco->variavelQueRecebe->id && $programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->bloco->variavelEsq->id){
                        $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10\n
                        .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                        lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                        beq \$t1,\$t2, label \n
                        label:\n
                        lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                        return $assembly.$label; 
                    }
                }
                
                if($programa->listaBlocos[0]->expressao->operadorEsquerda->id != $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelEsq instanceof Constt){
                    $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                    ".$programa->listaBlocos[0]->expressao->operadorDireita->id.": .word 1\n
                    .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                    lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                    beq \$t1,\$t2, label \n
                    label:\n
                    lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->const."</i>";
                    return $assembly.$label; 
                }
                else if($programa->listaBlocos[0]->expressao->operadorEsquerda->id != $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelEsq instanceof Id){
                    $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                    ".$programa->listaBlocos[0]->expressao->operadorDireita->id.": .word 1\n
                    .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                    lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                    beq \$t1,\$t2, label \n
                    label:\n
                    lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                    return $assembly.$label; 
                }
                else if($programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelEsq instanceof Id && $programa->listaBlocos[0]->bloco->variavelEsq->id !=  $programa->listaBlocos[0]->bloco->variavelQueRecebe->id){
                    $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .word 10
                    ".$programa->listaBlocos[0]->bloco->variavelEsq->id.": .word 1\n
                    .text\n\n lw \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                    lw \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                    beq \$t1,\$t2, label \n
                    label:\n
                    lw \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                    return $assembly.$label; 
                }            
            }//if INT
    
        }//if encontreiAtr
            
        if($tipoAtr == "string"){
            //if(a==a){a=a}
            if($programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelEsq->id ==  $programa->listaBlocos[0]->bloco->variavelQueRecebe->id && $programa->listaBlocos[0]->expressao->operadorEsquerda->id  == $programa->listaBlocos[0]->bloco->variavelQueRecebe->id){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .asciiz \"compilador\"\n
                .text\n\n la \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                la \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label \n
                label:\n
                la \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                return $assembly; 
            }
            //if(a==b){a=a}
            else if($programa->listaBlocos[0]->expressao->operadorEsquerda->id != $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelEsq->id ==  $programa->listaBlocos[0]->bloco->variavelQueRecebe->id){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .asciiz \"compilador\"
                ".$programa->listaBlocos[0]->expressao->operadorDireita->id.": .asciiz \"análise\"\n
                .text\n\n la \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                la \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label \n
                label:\n
                la \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                return $assembly; 
            }
            //if(a==b){b=a}
            else if($programa->listaBlocos[0]->expressao->operadorEsquerda->id != $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelEsq->id !=  $programa->listaBlocos[0]->bloco->variavelQueRecebe->id){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .asciiz \"compilador\"
                ".$programa->listaBlocos[0]->expressao->operadorDireita->id.": .asciiz \"análise\"\n
                .text\n\n la \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                la \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label \n
                label:\n
                la \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                return $assembly;
            }
            //if(a==a){a=b})
            else if($programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelEsq->id !=  $programa->listaBlocos[0]->bloco->variavelQueRecebe->id){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .asciiz \"compilador\"\n
                .text\n\n la \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                la \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label \n
                label:\n
                la \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                return $assembly;
            }
            //iff(a==a){b=b}
            else if($programa->listaBlocos[0]->expressao->operadorEsquerda->id == $programa->listaBlocos[0]->expressao->operadorDireita->id && $programa->listaBlocos[0]->bloco->variavelEsq->id ==  $programa->listaBlocos[0]->bloco->variavelQueRecebe->id && $programa->listaBlocos[0]->expressao->operadorEsquerda->id !=  $programa->listaBlocos[0]->bloco->variavelQueRecebe->id){
                $assembly =  "<i>.data\n\n". $programa->listaBlocos[0]->expressao->operadorEsquerda->id.": .asciiz \"compilador\"
                ".$programa->listaBlocos[0]->bloco->variavelEsq->id.": .asciiz \"compilador\"\n
                .text\n\n la \$t1, ".$programa->listaBlocos[0]->expressao->operadorEsquerda->id."
                la \$t2, ".$programa->listaBlocos[0]->expressao->operadorDireita->id."
                beq \$t1,\$t2, label \n
                label:\n
                la \$a0, ".$programa->listaBlocos[0]->bloco->variavelEsq->id."</i>";
                return $assembly;
            }
        }//string 
        
            

    }//ifAtr


}



?>