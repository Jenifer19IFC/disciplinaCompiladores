<?php
$entrada = isset($_POST['entrada']) ? $_POST['entrada'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1><title>Compiladores</title></h1>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
   
</head>
    <body>
        <form action= "" method="POST">
                
            <?php
                print("<h4><b>ANALISADOR ASCENDENTE SLR</b></h4>
                <br>Escreva a sentença abaixo:<br><br>");
            ?>
                <label for ="entrada"></label>
                <textarea name="entrada" id="entrada"><?=$entrada?></textarea><br>
                    <input type="submit" value="Enviar">  

<?php

require_once('AnalisadorLexicoClasse.php');
require_once('Pilha.php');
require_once('AnalisadorSemantico.php');
require_once('Escopo.php');
require_once('ChamaFuncao.php');
require_once('ArvoreDerivacao.php');

class AnalisadorAscendenteSLR{

    // Dúvida  nas ações
    public $ACTION = array();
    public $GOTO = array();
    public $cont = 0;
    protected $lexico;
    public $elementCompara;
    public $programa;

    function __construct(Lexico $lexico,$entrada){
        $this->lexico = $lexico;
        $this->carregaTabelas();
        $this->analiseSLR($entrada);
       //$this->next_token = $this->lexico->next_token();
    }

    function carregaTabelas(){//Somente terminais
        $this->ACTION = array('0' =>array('FUNCTION'=>'S2'),//ACAO .#
        '1' =>array('#'=>'ACCEPT'),//aceita
        '2' =>array('ID'=>'S3'),
        '3' =>array('AP'=>'S4'),
        '4' =>array('TIPO'=>'S7'),//??
        '5' =>array('FP'=>'S12'),
        '6' =>array('PV'=>'S9','FP'=>'R1'),
        '7' =>array('ID'=>'S8'),
        '8'=>array('PV'=>'R2','FP'=>'R2'),//usar TODOS follows da  VARIAVEL p/ indicar redução TIPO ID 2x r
        '9'=>array('TIPO'=>'S7','FP'=>'R2'),//KYU
        '12'=>array('AC'=>'S13'),
        '13'=>array('CONST'=>'S16','ID'=>'S17','IF'=>'S34','WHILE'=>'S43','PRINT'=>'S52','FUN'=>'S57'),
        '14'=>array('FC'=>'S15'),//Dúvida 
        '15'=>array('#'=>'R8'),
        '16'=>array('ATRIBUICAO'=>'R1','MAIS'=>'R1','MENOS'=>'R1','MULT'=>'R1','DIVIDE'=>'R1','FP'=>'R1','PV'=>'R2','FC'=>''),
        '17'=>array('ATRIBUICAO'=>'R1','MAIS'=>'R1','MENOS'=>'R1','MULT'=>'R1','DIVIDE'=>'R1','FP'=>'R1','PV'=>'R2'),
        '18'=>array('ATRIBUICAO'=>'S19'),
        '19'=>array('CONST'=>'S16','ID'=>'S17'),
        '20'=>array('MAIS'=>'S21','MENOS'=>'S22','MULT'=>'S23','DIVIDE'=>'S24','PV'=>'S25'),//PV
        '21'=>array('CONST'=>'S16','ID'=>'S17','ATRIBUICAO'=>'R2','MAIS'=>'R2','MENOS'=>'R2','MULT'=>'R2','DIVIDE'=>'R2','FP'=>'R2'),
        '22'=>array('CONST'=>'S16','ID'=>'S17','ATRIBUICAO'=>'R2','MAIS'=>'R2','MENOS'=>'R2','MULT'=>'R2','DIVIDE'=>'R2','FP'=>'R2'),
        '23'=>array('CONST'=>'S16','ID'=>'S17','ATRIBUICAO'=>'R2','MAIS'=>'R2','MENOS'=>'R2','MULT'=>'R2','DIVIDE'=>'R2','FP'=>'R2'),
        '24'=>array('CONST'=>'S16','ID'=>'S17','ATRIBUICAO'=>'R2','MAIS'=>'R2','MENOS'=>'R2','MULT'=>'R2','DIVIDE'=>'R2','FP'=>'R2'),
        '25'=>array('PV'=>'S30','FC'=>'S30'),//D
        '30'=>array('FC'=>'R5','CONST'=>'R5','ID'=>'R5','IF'=>'R5','WHILE'=>'R5','PRINT'=>'R5','FUN'=>'R5'),
        '31'=>array('FC'=>'R1','CONST'=>'R1','ID'=>'R1','IF'=>'R1','WHILE'=>'R1','PRINT'=>'R1','FUN'=>'R1'),
        '32'=>array('FC'=>'R1','CONST'=>'S16','ID'=>'S17','IF'=>'S34','WHILE'=>'S43','PRINT'=>'S52','FUN'=>'S57','FC'=>'R1','CONST'=>'R1','ID'=>'R1','IF'=>'R1','WHILE'=>'R1','PRINT'=>'R1','FUN'=>'R1','FC'=>'R2'),
        '34'=>array('AP'=>'S35'),
        '35'=>array('ID'=>'S36'),
        '36'=>array('COMPARA'=>'S37'),
        '37'=>array('CONST'=>'S16','ID'=>'S17'),//REDUZ PARA 37? outros também?
        '38'=>array('FP'=>'S39'),
        '39'=>array('AC'=>'S40'),
        '40'=>array('CONST'=>'S16','ID'=>'S17','IF'=>'S34','WHILE'=>'S43','PRINT'=>'S52','FUN'=>'S57','ATRIBUICAO'=>'S19','PV'=>'S41'),//GD
        '41'=>array('FC'=>'S42'),
        '42'=>array('FC'=>'R9','CONST'=>'R9','ID'=>'R9','IF'=>'R9','WHILE'=>'R9','PRINT'=>'R9','FUN'=>'R9'),
        '43'=>array('AP'=>'S44'),
        '44'=>array('ID'=>'S45'),
        '45'=>array('COMPARA'=>'S46'),
        '46'=>array('CONST'=>'S16','ID'=>'S17'),
        '47'=>array('FP'=>'S48'),
        '48'=>array('AC'=>'S49'),
        '49'=>array('CONST'=>'S16','ID'=>'S17','IF'=>'S34','WHILE'=>'S43','PRINT'=>'S52','FUN'=>'S57','ATRIBUICAO'=>'S19','PV'=>'S41'),
        '50'=>array('FC'=>'S51'),
        '51'=>array('FC'=>'R9','CONST'=>'R9','ID'=>'R9','IF'=>'R9','WHILE'=>'R9','PRINT'=>'R9','FUN'=>'R9'),
        '52'=>array('AP'=>'S53'),
        '53'=>array('CONST'=>'S16','ID'=>'S17'),
        '54'=>array('FP'=>'S55'),
        '55'=>array('PV'=>'S56'),
        '56'=>array('FC'=>'R5','CONST'=>'R5','ID'=>'R5','IF'=>'R5','WHILE'=>'R5','PRINT'=>'R5','FUN'=>'R5'),
        '57'=>array('PT'=>'S58'),
        '58'=>array('ID'=>'S59'),
        '59'=>array('AP'=>'S60'),
        '60'=>array('CIFRAO'=>'S61'),
        '61'=>array('ID'=>'S62'),
        '62'=>array('V'=>'S63'),
        '63'=>array('CIFRAO'=>'S61','FP'=>'R3'),//R4
        '65'=>array('FP'=>'S66','FC'=>'R4'),
        '66'=>array('PV'=>'S67'),
        '67'=>array('FC'=>'R7','CONST'=>'R7','ID'=>'R7','IF'=>'R7','WHILE'=>'R7','PRINT'=>'R7','FUN'=>'R7'),
        '68'=>array('FC'=>'R1','CONST'=>'R1','ID'=>'R1','IF'=>'R1','WHILE'=>'R1','PRINT'=>'R1','FUN'=>'R1'),
        '69'=>array('FC'=>'R1','CONST'=>'R1','ID'=>'R1','IF'=>'R1','WHILE'=>'R1','PRINT'=>'R1','FUN'=>'R1'),
        '70'=>array('FC'=>'R1','CONST'=>'R1','ID'=>'R1','IF'=>'R1','WHILE'=>'R1','PRINT'=>'R1','FUN'=>'R1'), 
        '71'=>array('FC'=>'R1','CONST'=>'R1','ID'=>'R1','IF'=>'R1','WHILE'=>'R1','PRINT'=>'R1','FUN'=>'R1'),

        );

        //Desempilha e qual estado eu vou parar?
        $this->GOTO = array('0'=>array('#'=>'1'),   
        '4' =>array('FP'=> '6','PV'=> '6','FP'=>'5'),//CONFLITO FP reduce/reduce
        '9' =>array('PV'=> '6'),//FP de cima também
        '13'=>array('ATRIBUICAO'=> '18','FC'=>'31','CONST'=>'31','ID'=>'31','IF'=>'31','WHILE'=>'31','PRINT'=>'31','FUN'=>'31','FC'=>'32','CONST'=>'32','ID'=>'32','IF'=>'32','WHILE'=>'32','PRINT'=>'32','FUN'=>'32','FC'=>'68','CONST'=>'68','ID'=>'68','IF'=>'68','WHILE'=>'68','PRINT'=>'68','FUN'=>'68','FC'=>'69','CONST'=>'69','ID'=>'69','IF'=>'69','WHILE'=>'69','PRINT'=>'69','FUN'=>'69','FC'=>'70','CONST'=>'70','ID'=>'70','IF'=>'70','WHILE'=>'70','PRINT'=>'70','FUN'=>'70','FC'=>'14'), 
        '18'=>array('PV'=> '20'),//acr.
        '19'=>array('MAIS'=>'20','MENOS'=>'20','MULT'=>'20','DIVIDE'=>'20','FP'=>'20'),//FP também?
        '20'=>array('PV'=> '25'), 
        '32'=>array('FC'=>'31','CONST'=>'31','ID'=>'31','IF'=>'31','WHILE'=>'31','PRINT'=>'31','FUN'=>'31','FC'=>'68','CONST'=>'68','ID'=>'68','IF'=>'68','WHILE'=>'68','PRINT'=>'68','FUN'=>'68','FC'=>'69','CONST'=>'69','ID'=>'69','IF'=>'69','WHILE'=>'69','PRINT'=>'69','FUN'=>'69','FC'=>'70','CONST'=>'70','ID'=>'70','IF'=>'70','WHILE'=>'70','PRINT'=>'70','FUN'=>'70','FC'=>'71','CONST'=>'71','ID'=>'71','IF'=>'71','WHILE'=>'71','PRINT'=>'71','FUN'=>'71','FC'=>'14'),
        '37'=>array('ATRIBUICAO'=>'38','MAIS'=>'38','MENOS'=>'38','MULT'=>'38','DIVIDE'=>'38','FP'=>'38'), 
        '46'=>array('ATRIBUICAO'=>'47','MAIS'=>'47','MENOS'=>'47','MULT'=>'47','DIVIDE'=>'47','FP'=>'47'), 
        '53'=>array('ATRIBUICAO'=>'54','MAIS'=>'54','MENOS'=>'54','MULT'=>'54','DIVIDE'=>'54','FP'=>'54'),
        '60'=>array('FP'=>'65'),
        '40'=>array('FC'=>'41'),
        '49'=>array('FC'=>'50'),
        '63'=>array('FP'=>'65'),
        '59'=>array('FC'=>'65'),
        '48'=>array('FC'=>'0'),
        '39'=>array('FC'=>'0')
      
        );

    }

    function nextToken(){
        $aux = $this->lexico->somente_tokens[$this->cont];
        $this->cont++;
        return $aux;
    }

    function nextTokenObject(){
        $aux = $this->lexico->lista_tokens[$this->cont];
        $this->cont++;
        return $aux;
    }

    function analiseSLR($entrada){
        $p = new Stack();
        $pilhaEscopos = new Stack();
        $p->push('#');
        $p->push(0);
        $i = 0;
        //$tokenAtual = $this->nextToken();
        $tokenAtualbject = $this->nextTokenObject();
        $tokenAnterior = $tokenAtualbject;
        $semantico = new Semantica();
        $escopo = new Escopo();
        $firstFun = 'fun';
        $auxNome = "";
        $auxArray = array();
        $chamaFun = new ChamaFuncao($auxNome,$auxArray);
        $auxFun = false;//Quando true, estou no escopo de Fun
        $auxGeral = false; //Quando true, estou no escopo geral
        $contA = 0;
        $contVarDeclaradas = 0;
        $contVarDeclaradasReconhecidas = 0;
        $auxDecl = false;
        $auxCompara = false; // Quando encontro um COMPARA
        
        $encontraFClistParametrosFuncao = false;
        $arvoreDerivacao = new ArvoreDerivacao();

        
    
       for($i = 0; $i<=strlen($entrada);$i++){ 

        //Encontra todas as variáveis declaradas no parâmetro
        $myToken =  $this->lexico->lista_tokens[$this->cont-1];
        if($myToken->lexema == 'string' | $myToken->lexema == 'int' | $myToken->lexema == 'boolean' ){
            $contVarDeclaradas++;
            $contVarDec = 0;
            $contVarDec++;
            //echo $contVarDec;
            $myTokenVar =  $this->lexico->lista_tokens[$this->cont];
            $escopo->listVariaveisDeclaradas[$myTokenVar->lexema] = $myToken->lexema;
            array_push($escopo->listVariaveisObjectsDecl,$myToken);
        }

        //Dentro do escopo função ----------------------------------------
        //Verifica de inicia um escopo de chamada de função 
        if($myToken->lexema == $firstFun){
            //echo "<br><br>ACHEI UM CHAMA FUNÇÃO!!<br>";
            $auxFun = true;
        }

        //Verifica as variáveis encontradas dentro do escopo de chama função
        if($auxFun == true){
            if($myToken->token == "ID"){
                array_push($chamaFun->listVarChamaFuncao,$myToken->lexema);
            }
        }
        //Valida o fim do escopo do chama função 
        if($myToken->token == "FC"){
            $auxFun = false;
        }

       
         // -------------------------------------------------------------------

         //Verifica variáveis no escopo geral
        if($myToken->token == "AC"){
            $auxGeral = true;
        }

        if($auxGeral == true && $auxFun == false){
            if($myToken->token == "ID" && $myToken->lexema != "true" && $myToken->lexema != "false"){
                array_push($escopo->listVariaveisUsadas,$myToken->lexema);
            }
        }
        if($myToken->token == "FC"){
            $auxGeral = false;
        }
        //----------------------------------------------------------------------

        //Verifica valores recebidos na atribuicao
        if($myToken->token == "ID"){
            $myTokenVar = $this->lexico->lista_tokens[$this->cont];
            if($myTokenVar->lexema == "=" ){
                //echo "<br>ECONTREI PARA MINHA ATRIBUICAO!! " . $myToken->lexema. " <br> ";
                //echo "<br> MEU ANTERIOR: ". $myTokenVar->lexema. "<BR>";
                $auxDecl = true;

            }
        }

        if($auxDecl == true ){
            //echo "<br>MEUS TOKENS: ". $myToken->lexema. "<br>";
            array_push($escopo->listVarValoresRecebidos,$myToken);
        }

        if($myToken->token == "PV" ){
            $auxDecl = false;
        }

        //--------------------------------------------------------------------------------------

        if($myToken->token == "COMPARA"){
            $auxCompara = true;
        }
        if($auxCompara == true){
            if($myToken->token == "ID" | $myToken->token == "CONST" | $myToken->lexema == "true" | $myToken->lexema == "false"){
                $this->elementCompara = $myToken;
            }
        }

        if($myToken->token == "FP"){
            $auxCompara = false;
        }
            
       
        //var_dump($tokenAtualbject);
            try{
                if(array_key_exists($p->top(),$this->ACTION) && array_key_exists($tokenAtualbject->token,$this->ACTION[$p->top()]) &&$this->ACTION[$p->top()][$tokenAtualbject->token][0] == 'S'){
                        $guardaParaEmpilhar = $this->ACTION[$p->top()][$tokenAtualbject->token];//AQUI??
                        //echo '<br>GUARDANDO: '.$guardaParaEmpilhar;
                        $stringResult = (int)substr($guardaParaEmpilhar,1);

                        $p->push($stringResult);//ver pra não pegar a letra primeria posicao    S[23]
                        //echo "<br><b>Empilhei:</b> ".$stringResult;

                        $tokenAnterior =  $tokenAtualbject;
                        //$tokenAtual = $this->nextToken();
                        $tokenAtualbject = $this->nextTokenObject();

                        //echo "MEU TOKEN:<BR>";
                        //var_dump($tokenAtualbject);
            
                        //var_dump($tokenAtualbject);
                        $this->programa = $arvoreDerivacao->arvoreDerivacao($tokenAtualbject,$escopo->listVariaveisDeclaradas,$escopo->listVariaveisUsadas,$tokenAnterior);
                        //echo  "<br> TOKEN ATUAL: ";
                        //var_dump($this->nextTokenObject()->token);
                }
                else if(array_key_exists($p->top(),$this->ACTION) && array_key_exists($tokenAtualbject->token,$this->ACTION[$p->top()]) &&$this->ACTION[$p->top()][$tokenAtualbject->token][0] == 'R'){
                    $guardaEmbaixo = ($this->ACTION[$p->top()][$tokenAtualbject->token][1]);
                    $stringResultEmbaixo = (int)$guardaEmbaixo;
                    //echo '<br>DESEMPILHAR: '.$stringResultEmbaixo;
                    for ($j=0; $j < $stringResultEmbaixo ; $j++){//menos 1 ate o final -- parse int
                        $p->pop();
                        //echo "<BR><br><b>Desempeilhei 1</b> ";
                        //var_dump($p); 
                    }

                    //DESVIO
                    if(array_key_exists($tokenAtualbject->token,$this->GOTO[$p->top()])){//como faz o array key existents
                        $p->push($this->GOTO[$p->top()][$tokenAtualbject->token]);
                    }

                    //echo  "<br><br>ACHEI UMA VARIÁVEL OU NÚMERO: ";
                    //echo "MEUS IDS E CONSTS:<BR>";
                    //var_dump($tokenAnterior);//Aquin eu pego todas as variáveis no escopo
                    //$escopo->guardaVariaveisNaListaVar($tokenAnterior);
                    //$escopo->criaNovoEscopo($tokenAtualbject);
                    //echo "<br>Aquii";
                    
                
                    
                }else if(array_key_exists($tokenAtualbject->token,$this->ACTION[$p->top()]) && $this->ACTION[$p->top()][$tokenAtualbject->token] == 'ACCEPT' && $semantico->verificaVarMesmoNome($escopo->listVariaveisDeclaradas,$contVarDeclaradas,$contVarDeclaradasReconhecidas) == false && $semantico->verificaValoresAtribuicao($escopo->listVariaveisDeclaradas,$escopo->listVariaveisUsadas,$escopo->listVarValoresRecebidos,$this->elementCompara, $myToken) == true && $semantico->verificaDeclaracoesOuNao($escopo->listVariaveisDeclaradas,$escopo->listVariaveisUsadas,$chamaFun->listVarChamaFuncao) == true){//ACC?
                    print('<br><br><b>Linguagem aceita!</b>');
                    //var_dump($this->lexico->lista_tokens);
                    break;
                }else{
                    print("<br><br><b>Erro!</b>");
                    break;
               }
            }catch(Exception $e){
                print('<br><br><b>Linguagem aceita!</b>');
                break;
            }


        }//for
        
       
        //----------------------------------------------------------------------------
        //echo "<br><br> VISAO GERAL<br>";
        
        //$semantico->verificaDeclaracoesOuNao($escopo->listVariaveisDeclaradas,$escopo->listVariaveisUsadas,$chamaFun->listVarChamaFuncao);

        //$semantico->verificaValoresAtribuicao($escopo->listVariaveisDeclaradas,$escopo->listVariaveisUsadas,$escopo->listVarValoresRecebidos,$this->elementCompara);
      
        //----------------------------------------------------------------------------
       
       
    }//funcao

}//CLASS

$lexico = new Lexico($entrada.'#');
if(isset($_POST['entrada']))    
    $SLR  = new AnalisadorAscendenteSLR($lexico,$entrada);

    echo "<br><br>";
    print_r($SLR->programa);

?>

</body>
        </fieldset>
    </form>
</html>