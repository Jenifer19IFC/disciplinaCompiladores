<?php
require_once('../objetos./Escopo.php');
require_once('../objetos./ChamaFuncao.php');
require_once('../objetos./Id.php');
require_once('../objetos./Const.php');
require_once('../objetos./Var.php');
require_once('../objetos./If.php');
//require_once('./pasta/If.php');
require_once('../objetos./While.php');
require_once('../objetos./Atr.php');
require_once('../objetos./ChamaFuncao.php');
require_once('../objetos./Imprime.php');
require_once('../objetos./Expressao.php');
require_once('../objetos./Operacao.php');
require_once('../objetos./Bloco.php');
require_once('../objetos./Programa.php');

class Semantica{
    //PRIMEIRO REALIZAR A VALIDAÇÃO E DEPOIS A ÁRVORE DE DERIVAÇÃO
        //Um objeto para cada NÃO TEMRINAL, dentro de um terminal pode ter outro objeto NÃO TERMINAL
        //Os terminais são atributos

    // OK Quais variaveis foram declaradas no parâmetro; 
    // Está sendo atribuído o valor correto para esta variável?;
    // OK Declarada mais de uma vez
    // OK ou não declarada
    // OK Se declarada e não usada, dar um aviso;

    public $aux = array();
    
    function verificaFimDaListaParametrosFuncao(Token $tokenAtualbject){
        if($tokenAtualbject->token == "FP"){
            return true;
       }
        return false;
    }

    function verificaDeclaracoesOuNao($listVariaveisDeclaradas,$listVariaveisUsadas,$listVarChamaFuncao){
            array_shift($listVarChamaFuncao);

            //Verifica se as declaradas foram usadas no geral e fun
            foreach($listVariaveisDeclaradas as $key=>$value){
                //echo $key. " ". $value. " <br><br>";
                if(in_array($key,$listVariaveisUsadas ) | in_array($key,$listVarChamaFuncao)){
                    //echo "<br> Variável '<b>".$key . "</b>' declarada e é uutilizada.<br>";
                }else{
                    echo "<br><br>Variável '<b>".$key . "</b>' foi declarada, mas não é utilizada   .<br>";
                }
                    
            }
            
            //Verifica se o que está no chama fun está declarada
            foreach($listVarChamaFuncao as $key){
                if(array_key_exists($key,$listVariaveisDeclaradas)){
                        //echo $key . "   foi usada CH_FUN e está declarada<br><br>";
                }
                else{
                    echo "<br>Variável '<b>".$key . "</b>'   foi usada no corpo interno, mas não foi declarada.<br>";
                    return false;
                }
            }

            //Verifica se o que está no escopo externo foi declarado
            foreach($listVariaveisUsadas as $key){
                if(array_key_exists($key,$listVariaveisDeclaradas)){
                        //echo $key . "   foi usada CH_FUN e está declarada<br><br>";
                }
                else{
                    echo "<br>Variável '<b>".$key . "</b>' foi usada no escopo externo, mas não foi declarada.<br>";
                    return false;
                }
            } 
            return true;  

    }

   function verificaVarMesmoNome($listVariaveisDeclaradas,$contVarDeclaradas,$contVarDeclaradasReconhecidas){
     //Verifica se há variáveis declaradas com mesmo nome;
    foreach($listVariaveisDeclaradas as $key=>$value){ 
        $contVarDeclaradasReconhecidas++;  
    }
    if($contVarDeclaradas == 2 && $contVarDeclaradasReconhecidas == 1){
        print("<br>Há variáveis declaradas com mesmo nome!<br>");
        return true;
    } 
    return false;
   }

   function verificaValoresAtribuicao($listVariaveisDeclaradas,$listVariaveisUsadas,$listVarValoresRecebidos,$elementCompara,$token){
        $ScontStrings = 0;
        $auxS = 0;
        $ScontInteiros = 0;
        $IcontStrings = 0;
        $IcontInteiros = 0;
        $auxI = 0;
    
        $SauxCompI = 0;
        $SauxIi = 0;

        $IauxCompS = 0;
        $IauxCompI = 0;
        $IauxIi = 0;

        $SauxCompS = 0;
        $SauxCompI = 0;
        $auxSs = 0;
        $auxSi = 0;
        $IauxSs = 0;
        $IauxSi = 0;
        $IauxCompI = 0;
        $a = false;
        $passouString = false;
        $passouInt = false;
        $encontreiVarString = false;
        $encontreiVarInt = false;
        $aB = false;

        foreach($listVariaveisDeclaradas as $key=>$value){
            if(in_array($key,$listVariaveisUsadas)){
                //echo "<br>Chave " .  $key. " do tipo " . $value ."<br>";
                if(!empty($elementCompara->lexema)){
                    $a = true;
                    if($value == "string"){
                        $passouString = true;
                            if($elementCompara->token == "ID"){ 
                                $SauxCompS++;
                                if(array_key_exists($elementCompara->lexema, $listVariaveisDeclaradas)){
                                        foreach($listVariaveisDeclaradas as $key1=>$value1){
                                            if($key1 == $elementCompara->lexema){
                                                //echo $key . " " .$listVarValoresRecebidos[$i]->lexema;
                                                if($value1 == "string"){
                                                    $auxSs++;
                                                    $encontreiVarString = true;
                                                    
                                                    //echo $key . " do tipo " . $value. "<br>";
                                                }
                                                else{
                                                    $auxSi++;
                                                    $encontreiVarInt = true;
                                                    //echo $key . " do tipo " . $value. "<br>";
                                                }
                                            } 
                                        }
                                }   
                            }else if($elementCompara->token == "CONST"){
                                $SauxCompI++;
                                
                            } 

                        if($auxSs == $SauxCompS && $SauxCompI == 0 &&  $auxSi == 0){
                            //echo "TUDO STRING!!";
                        }else if($IauxCompS == 1 && $IauxSi == 1){
                            echo "<br><br>Variável deve receber valor de tipo String";
                            return false;
                        }
                        
                        else if($SauxCompS == $auxSi && $auxSs == 0 && $SauxCompI == 0){
                            return true;
                        }
                        
                        else if($ScontStrings == 1 && $auxS == 1){
                            //string
                        }else if($SauxCompS == 1 && $auxSs == 0 && $SauxCompI == 0 && $auxSi == 0){
                            echo "<br>Variável não declarada!<br>";
                            return false;
                        }
                        else{
                            //echo "<br><br>Comparação deve receber variável do tipo String!<br>";
                            echo "<br><br>Variável deve receber valor do tipo String!<br>";
                            return false;
                            exit;
                        }
                    }//string

                        
                    if($value == "int"){
                        $passouInt = true;
                        if($elementCompara->token == "ID"){ 
                            $IauxCompS++;
                            if(array_key_exists($elementCompara->lexema, $listVariaveisDeclaradas)){
                                    foreach($listVariaveisDeclaradas as $key1=>$value1){
                                        if($key1 == $elementCompara->lexema){
                                            //echo $key . " " .$listVarValoresRecebidos[$i]->lexema;
                                            if($value1 == "string"){
                                                $IauxSs++;
                                                $encontreiVarString = true;
                                                //echo $key . " do tipo " . $value. "<br>";
                                            }else{
                                                $IauxSi++;
                                                $encontreiVarInt = true;
                                                //echo $key . " do tipo " . $value. "<br>";
                                            }
                                        } 
                                    }
                            }
                        }else if($elementCompara->token == "CONST"){
                            $IauxCompI++;
                        }
                        
                        if($IauxSi != 0 | $IauxCompI != 0){
                            //echo "TUDO int    !!";
                        }else if($IauxCompS == 1 && $IauxSi == 0 && $IauxSs == 0 && $IauxCompI == 0){
                            echo "<br>Variável não declarada<br>";
                            return false;
                        }
                        else{
                            //echo "<br><br>Comparação deve receber variável do tipo Int<br>";
                            echo "<br><br>Variável deve receber valor do tipo Int<br>";
                            return false;
                            exit;
                        }
                    }//int

                    if($value == "boolean"){ 
                        if($encontreiVarInt | $encontreiVarString){
                            echo "<br>Atribuição de valores incorretos<br>";
                            return false;
                        }
                        if($elementCompara->lexema == "true" | $elementCompara->lexema == "false" | $passouString == true | $passouInt == true ){
                            //echo "<br><br>Variável bolean OK<br>";
                            return true;
                        }else{
                            echo "<br>Variável boolean dever receber valor booleano<br>";
                            return false;
                        }
                    }

                }

                if($value == "string"){ 
                        for ($i=3; $i < sizeof($listVarValoresRecebidos)-1; $i++) {
                            if($listVarValoresRecebidos[$i]->token == "MENOS"| $listVarValoresRecebidos[$i]->token == "MULT" | $listVarValoresRecebidos[$i]->token == "DIVIDE"){ 
                                echo "<br>Erro, impossível realizar esta operação com Strings<br>";
                                return false;
                                exit;
                            }
                            if($listVarValoresRecebidos[$i]->token == "ID"){ 
                                $ScontStrings++;
                                if(array_key_exists($listVarValoresRecebidos[$i]->lexema, $listVariaveisDeclaradas)){
                                        foreach($listVariaveisDeclaradas as $key=>$value){
                                            if($key == $listVarValoresRecebidos[$i]->lexema){
                                                //echo $key . " " .$listVarValoresRecebidos[$i]->lexema;
                                                if($value == "string"){
                                                    $auxS++;
                                                    //echo $key . " do tipo " . $value. "<br>";
                                                }else{
                                                    $auxI++;
                                                    //echo $key . " do tipo " . $value. "<br>";
                                                }
                                            } 
                                        }
                                }
                            }else if($listVarValoresRecebidos[$i]->token == "CONST"){
                                $IcontInteiros++;
                            }
                            
                        }
                        if($auxI == 0 && $IcontInteiros == 0 && $ScontStrings == $auxS){
                            //echo "TUDO STRING!!";
                        }
                        else{
                            echo "<br><br>Erro, variável recebeu valor de tipo incorreto!<br>";
                            return false;
                            exit;
                        }
                }//string

            

                if($value == "int"){     
                    for ($i=3; $i < sizeof($listVarValoresRecebidos)-1; $i++) {
                        if($listVarValoresRecebidos[$i]->token == "ID"){ 
                            $ScontStrings++;
                            if(array_key_exists($listVarValoresRecebidos[$i]->lexema, $listVariaveisDeclaradas)){
                                    foreach($listVariaveisDeclaradas as $key=>$value){
                                        if($key == $listVarValoresRecebidos[$i]->lexema){
                                            //echo $key . " " .$listVarValoresRecebidos[$i]->lexema;
                                            if($value == "string"){
                                                $auxS++;
                                                //echo $key . " do tipo " . $value. "<br>";
                                            }else{
                                                $auxI++;
                                                //echo $key . " do tipo " . $value. "<br>";
                                            }
                                        }
                                    }
                            }
                        }else if($listVarValoresRecebidos[$i]->token == "CONST"){
                            $IcontInteiros++;
                        }
                        
                    }
                    if($ScontStrings == $auxI){
                        //echo "TUDO INTEIRO!!";
                    }else if($ScontStrings == 2 && $auxS == 2){
                        echo "<br>Variável do tipo Int deve receber variável do tipo Int<br>";
                        return false;
                    }
                    else if($ScontStrings == $auxS){
                        //string
                    }else if($ScontStrings == 1 && $auxS == 0 && $auxI == 0 && $IcontInteiros == 0){
                        echo "Variável não declarada!";
                        return false;
                    }
                    else{
                        echo "<br><br>Erro, variável recebeu valor de tipo incorreto!<br>";
                        return false;
                        exit;
                    }
                }//int

                if($value == "boolean"){     
                    for ($i=3; $i < sizeof($listVarValoresRecebidos)-1; $i++) {
                        if($listVarValoresRecebidos[$i]->token == "ID"){ 
                            $ScontStrings++;
                            if(array_key_exists($listVarValoresRecebidos[$i]->lexema, $listVariaveisDeclaradas)){
                                    foreach($listVariaveisDeclaradas as $key=>$value){
                                        if($key == $listVarValoresRecebidos[$i]->lexema){
                                            //echo $key . " " .$listVarValoresRecebidos[$i]->lexema;
                                            if($value == "string" | $value == "int" ){
                                                $auxS++;    
                                                //echo $key . " do tipo " . $value. "<br>";
                                            }else if($value == "boolean"){
                                                $aB = true;
                                                //echo $key . " do tipo " . $value. "<br>";
                                            }
                                        }
                                    }
                            }   
                        }else if($listVarValoresRecebidos[$i]->token == "CONST"){
                            $IcontInteiros++;
                        }
                        
                    }

                    if($aB == true){
                        return true;
                    }
                    else{
                        echo "<br>Varável boolean recebeu atribuição de valor incorreto<br>";
                        return false;
                    }
                   
                }//boolean

                
            }
        }
        return true;

    }//funcao

    

}
?>
