<?php
$entrada = isset($_POST['entrada']) ? $_POST['entrada'] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1><title>Analsador Descendente Preditivo</title></h1>
</head>
    <body>
        <form action= "" method="POST">
            <fieldset>
            <?php
                print("<h4>ANALISADOR DESCENDENTE PREDITIVO</h4>
                Escreva a sentença abaixo:<br><br>");
            ?>
                <label for ="entrada"></label>
                    <textarea name="entrada" id="entrada"><?=$entrada?></textarea><br>
                    <input type="submit" value="Enviar">  


<?php
require_once('AnalisadorLexicoClasse.php');
require_once('Pilha.php');

class Preditivo{

    protected $lexico;
    protected $stack; 
    public $cont = 1;
    //public $next_token;  

    function __construct(Lexico $lexico,$entrada){
        $this->lexico = $lexico;
        $this->delta = array();
        $this->tabelaAcao();
        $this->acaoPreditivo($entrada);
       //$this->next_token = $this->lexico->next_token();
    }

    function tabelaAcao(){
        $this->delta = array('P' =>array('FUNCTION'=>(['FUNCTION','ID','AP', 'LISTA_VARIAVEIS','FP','AC','LISTA_BLOCOS','FC'])),
        'LISTA_VARIAVEIS' =>array('TIPO'=>(['VARIAVEL','LISTA_VARIAVEIS2'])),
        'LISTA_VARIAVEIS2' =>array('PV'=>(['PV','LISTA_VARIAVEIS']),'FP'=>([])),
        'VARIAVEL' =>array('TIPO'=>(['TIPO','ID'])),
        'LISTA_BLOCOS' =>array('const'=>(['BLOCO','LISTA_BLOCOS']),'ID'=>(['BLOCO','LISTA_BLOCOS']),'IF'=>(['BLOCO','LISTA_BLOCOS']),'WHILE'=>(['BLOCO','LISTA_BLOCOS']),'PRINT'=>(['BLOCO','LISTA_BLOCOS']),'FUN'=>(['BLOCO','LISTA_BLOCOS']),'FC'=>([])),
        'BLOCO' =>array('const'=>(['ATR']),'ID'=>(['ATR']),'IF'=>(['IFF']),'WHILE'=>(['WHILEE']),'PRINT'=>(['IMPRIME']),'FUN'=>(['CHAMA_FUNCAO'])),
        'ATR' =>array('const'=>(['VAR','ATRIBUICAO','VAR', 'OPERACAO','PV']),'ID' => (['VAR','ATRIBUICAO','VAR', 'OPERACAO','PV'])),
        'VAR' =>array('const'=>(['const']),'ID'=>(['ID'])),
        'OPERACAO' =>array('PV'=>([]),'MAIS'=>(['MAIS','VAR']),'MENOS'=>(['MENOS','VAR']),'MULT'=>(['MULT','VAR']),'DIVIDE'=>(['DIVIDE','VAR'])),
        'IFF' =>array('IF'=>(['IF','AP','ID', 'COMPARA','VAR','FP','AC','BLOCO','FC'])),
        'WHILEE' =>array('WHILE'=>(['WHILE','AP','ID', 'COMPARA','VAR','FP','AC','BLOCO','FC'])),
        'IMPRIME' =>array('PRINT'=>(['PRINT','AP','VAR', 'FP','PV'])),
        'CHAMA_FUNCAO' =>array('FUN'=>(['FUN','PT','ID', 'AP','PARAM','FP','PV'])),
        'PARAM' =>array('FP'=>([]),'CIFRAO'=>(['CIFRAO','ID','V','PARAM'])),    
        );
        //var_dump($this->delta);
    }

    function nextToken(){
        $aux = $this->lexico->somente_tokens[$this->cont];
        $this->cont++;
        return $aux;
    }
      

    function acaoPreditivo($entrada){
        $listTerminais = ['FUNCTION','TIPO','PV','FP','const','ID','PRINT','FUN','FC','MAIS','MENOS','MULT','DIVIDE','CIFRAO','AP','AC','ATRIBUICAO','COMPARA','FUN','PT','V', 'IF','WHILE'];
        $this->stack = new Stack();
        $this->stack->push("#"); //Add topo da pilha
        //echo "<br>TOPO: ".$this->stack->top(). "<br>";
        $x = $this->lexico->somente_tokens[0];  //primeiro token em $x
        
        $this->stack->push('P'); //empilha simbolo inicial
        $cont = 0;
        //echo "<br>TOPO agora: ".$this->stack->top(). "<br>";   
        //var_dump($x);

        for($i = 0; $i<strlen($entrada);$i++){
            //echo "<br>TOPO aqui: ".$this->stack->top()."Token ".$x. "<br>";   
            //print("<BR>MINHA PILHA AGORA:<BR>");
           // var_dump($this->stack);
            //print("<BR>MEUS TOKENS:<BR>");
            //var_dump($this->lexico->somente_tokens);
            //echo 'CONT '. $cont;

            if(in_array($this->stack->top(),$listTerminais)){
                if($this->stack->top() == $x){
                    //print('É terminal');
                    $this->stack->pop();//Desempilha
                    $x = $this->nextToken();//Next token
                    //$cont += 1;
                }else{  
                    print("<br><br>Erro sintático!<br>");
                    break;
                }
            }else{
                if((array_key_exists($this->stack->top(),$this->delta)) && (array_key_exists($x,$this->delta[$this->stack->top()]))){
                    $producao = $this->delta[$this->stack->top()][$x];
                    if(count($producao) > 0){
                        $producao = array_reverse($producao);
                    }
                    //echo "<br>TOPO aqui: ".$this->stack->top(). "<br>";   
                    //print('É não terminal');
                    $this->stack->pop();//Desempilha
                    if(count($producao) > 0){
                        foreach($producao as $valor){
                            $this->stack->push($valor); 
                        }
                    } 
                    //AQUI FICA PARADO
                }
            }
            }//for
            if($this->stack->top() == '#'){
                print('<br><br><b> Sentença aceita!<b><br>');
            }else{
                print('<br><br><b>Sentença não aceita!<b><br>');
            }

        }//funcao

    }

$lexico = new Lexico($entrada.'#');
if(isset($_POST['entrada']))
    $preditivo = new Preditivo($lexico,$entrada);


?>

</body>
        </fieldset>
    </form>
</html>