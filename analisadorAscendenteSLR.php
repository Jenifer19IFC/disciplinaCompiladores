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
</head>
    <body>
        <form action= "" method="POST">
            <fieldset>
            <?php
                print("<h4>ANALISADOR ASCENDENTE SLR</h4>
                Escreva a sentença abaixo:<br><br>");
            ?>
                <label for ="entrada"></label>
                <textarea name="entrada" id="entrada"><?=$entrada?></textarea><br>
                    <input type="submit" value="Enviar">  

<?php

require_once('AnalisadorLexicoClasse.php');
require_once('Pilha.php');

class AnalisadorAscendenteSLR{

    // Dúvida  nas ações
    public $ACTION = array();
    public $GOTO = array();
    public $cont = 0;
    protected $lexico;

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

    function analiseSLR($entrada){
        $p = new Stack();
        $p->push('#');
        $p->push(0);
        $i = 0;
        $tokenAtual = $this->nextToken();
       //pega o primeiro token aqui e depois vai dando o next token ali em baixo 
       //echo "<BR>ATUAL: ".$tokenAtual;
       //echo "Lista de tokens: ";
       //var_dump($this->lexico->somente_tokens);

       for($i = 0; $i<=strlen($entrada);$i++){  

      // echo "<br>TESTANDO TOPO  ";
       //print_r($p->top());
        //echo "<br>TESTANDO TOKEN ATUAL[0]  " . $tokenAtual;


        
        //echo "<br>testando aqui...  " .$this->ACTION[$p->top()][$tokenAtual][1];

        //echo "<br>BAIXO: ";
        //var_dump($this->ACTION[$p->top()]);
            try{
                if(array_key_exists($p->top(),$this->ACTION) && array_key_exists($tokenAtual,$this->ACTION[$p->top()]) &&$this->ACTION[$p->top()][$tokenAtual][0] == 'S'){
                        $guardaParaEmpilhar = $this->ACTION[$p->top()][$tokenAtual];//AQUI??
                        //echo '<br>GUARDANDO: '.$guardaParaEmpilhar;
                        $stringResult = (int)substr($guardaParaEmpilhar,1);

                        $p->push($stringResult);//ver pra não pegar a letra primeria posicao    S[23]
                        //echo "<br><b>Empilhei:</b> ".$stringResult;

                        $tokenAtual = $this->nextToken();
                        //echo  "<br> Próximo token: ".$this->nextToken();
                }
                else if(array_key_exists($p->top(),$this->ACTION) && array_key_exists($tokenAtual,$this->ACTION[$p->top()]) &&$this->ACTION[$p->top()][$tokenAtual][0] == 'R'){
                    $guardaEmbaixo = ($this->ACTION[$p->top()][$tokenAtual][1]);
                    $stringResultEmbaixo = (int)$guardaEmbaixo;
                    //echo '<br>DESEMPILHAR: '.$stringResultEmbaixo;
                    for ($j=0; $j < $stringResultEmbaixo ; $j++){//menos 1 ate o final -- parse int
                        $p->pop();
                        //echo "<BR><br><b>Desempeilhei 1</b> ";
                        //var_dump($p); 
                    }
                    //echo "<br><br>Minha pilha:";
                    //var_dump($p);
                    if(array_key_exists($tokenAtual,$this->GOTO[$p->top()])){//como faz o array key existents
                        $p->push($this->GOTO[$p->top()][$tokenAtual]);
                    }
                    //echo "<br><br>Agora meu topo: ".$tokenAtual;
                }else if(array_key_exists($tokenAtual,$this->ACTION[$p->top()]) && $this->ACTION[$p->top()][$tokenAtual] == 'ACCEPT'){//ACC?
                    print('<br><br><b>Linguagem aceita!</b>');
                    break;
                }else{
                    print("<br><br><b>Erro!</b>");
                    break;
               }
            }catch(Exception $e){
                print('<br><br><b>Linguagem aceita!</b>');
                break;
            }


        }//while

    }//funcao

}//CLASS

$lexico = new Lexico($entrada.'#');
if(isset($_POST['entrada']))    
    $SLR  = new AnalisadorAscendenteSLR($lexico,$entrada);

?>

</body>
        </fieldset>
    </form>
</html>