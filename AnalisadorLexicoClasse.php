 


 <?php

            require_once('Token.php');
            
            class Lexico{

            public $lista_tokens = array();
            public $somente_tokens = array();


            function __construct($entrada){
                $this->lista_tokens = array();   
                $this->somente_tokens = array();   
                $this->delta = array();
                $this->carregaTransicoes();
                $this->finais = array('1'=> 'ID', '2'=>'ID', '3' =>'IF', '4' => 'ID', '5' =>'ID','6'=> 'ID', '7'=> 'ID', '8'=>'ID',  '9'=> 'WHILE', '10' => 'ID','11'=> 'ID', '12'=>'ID', '13'=> 'PRINT', '14'=>'ID', '15' => 'ID', '16' => 'FUN', '17' =>'ID','18'=> 'ID', '19'=> 'ID', '20'=>'ID', '21'=> 'FUNCTION', '22' => 'ID','23'=> 'TIPO', '25'=>'ID','26'=>'ID','27'=>'ID', '28' => 'ID','29'=> 'ID', '30'=>'TIPO', '31' =>'ID', '32' => 'ID', '33' =>'ID','34'=> 'ID', '35'=> 'ID', '36'=>'ID',  '37'=> 'TIPO', '38' => 'AC','39'=> 'FC', '40'=>'AP', '41'=> 'FP', '42'=>'V', '43' => 'PV', '44' => 'MAIS', '45' =>'MENOS','46'=> 'MULT', '47'=> 'DIVIDE', '48'=>'ATRIBUICAO',  '49'=> 'COMPARA', '50' => 'MENOR','51'=> 'MAIOR','53'=>'CIFRAO','54'=>'PT', '55' => 'CONST','56' => 'ESPACO',"57"=>'Quebra de linha','60'=>'#');
                $this->lexicoAcao($entrada);
            }
            
            function carregaTransicoes(){
                $this->delta = array('0' =>array('a'=> "1",'b'=>'31','c'=>'1','d'=>'1','e'=>'1','f'=>'14','g'=>'1','h'=>'1','i'=>'2','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'4','q'=>'1','r'=>'1','s'=>'25','t'=>'1','u'=>'1','v'=>'1','w'=>'5','x'=>'1','y'=>'1','z'=>'1','0'=>'55','1'=>'55','2'=>'55','3'=>'55','4'=>'55','5'=>'55','6'=>'55','7'=>'55','8'=>'55','9'=>'55','{' =>'38','}'=>'39','('=>'40',')'=>'41',','=>'42',';'=>'43','+'=>'44','-'=>'45','*'=>'46','/'=>'47','='=>'48','<'=>'50','>'=>'51','!'=>'52',' '=>'56','$'=>'53','.'=>'54',"\n"=>'57','#'=>'60'),
                '1' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '2' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'3','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'22','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '3' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '4' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'10','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '5' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'6','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '6' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'7','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '7' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'8','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '8' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'9','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '9' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '10' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'11','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '11' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'12','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '12' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'13','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '13' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '14' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'15','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '15' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'16','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '16' =>array('a'=> "1",'b'=>'1','c'=>'17','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '17' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'18','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '18' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'19','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '19' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'20','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '20' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'21','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '21' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '22' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'23','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '23' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '25' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'26','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '26' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'27','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '27' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'28','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '28' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'29','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '29' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'30','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '30' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '31' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'32','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '32' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'33','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '33' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'34','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '34' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'35','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '35' =>array('a'=> "36",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '36' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'37','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '37' =>array('a'=> "1",'b'=>'1','c'=>'1','d'=>'1','e'=>'1','f'=>'1','g'=>'1','h'=>'1','i'=>'1','j'=>'1','k'=>'1','l'=>'1','m'=>'1','n'=>'1','o'=>'1','p'=>'1','q'=>'1','r'=>'1','s'=>'1','t'=>'1','u'=>'1','v'=>'1','w'=>'1','x'=>'1','y'=>'1','z'=>'1','0'=>'1','1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1','8'=>'1','9'=>'1'),
                '38' =>array(),
                '39' =>array(),
                '40' =>array(),
                '41' =>array(),
                '42' =>array(),
                '43' =>array(),
                '44' =>array(),
                '45' =>array(),
                '46' =>array(),
                '47' =>array(),
                '48' =>array('='=>'49'),
                '49' =>array(),
                '50' =>array(),
                '51' =>array(),
                '52' =>array('='=>'49'),
                '53' =>array(),
                '54' =>array(),
                '55' =>array('0'=>'55','1'=>'55','2'=>'55','3'=>'55','4'=>'55','5'=>'55','6'=>'55','7'=>'55','8'=>'55','9'=>'55'),
                '56' =>array(),
                '57' =>array(),
                '60' =>array()
                );
            }

            function lexicoAcao($entrada){
                $estado = 0;
                $lexema = '';
                
                $posicao = 1;
                $linha = 1;
    
                for($i = 0; $i<strlen($entrada);$i++){
                    try{
                        if(array_key_exists($estado,  $this->delta) && array_key_exists($entrada[$i],  $this->delta[$estado])){
                            $estado =  $this->delta[$estado][$entrada[$i]];
                            $lexema = $lexema.$entrada[$i];
                            if($entrada[$i] == "\n"){
                                $linha = $linha + 1;
                            }

                    }else{
                            throw new Exception("<br><br>Há um erro léxico!");
                    }
                    
                    }catch(Exception $e){
                        if(array_key_exists($estado,  $this->finais)){
                            //require_once('Token.php');
                            $this->tk = new Token($this->finais[$estado], $lexema,$posicao,$linha);
                            if(!($estado == '56' | $estado == '57' )){
                                array_push($this->lista_tokens, $this->tk);
                                array_push($this->somente_tokens,$this->finais[$estado]);
                            }
                            $posicao = $posicao + 1;
                            $estado = 0;
                            $lexema = '';
                            $i = $i-1;
                        }
                        else{   
                            if($i+1 <strlen($entrada) && $entrada[$i+1] == "\n"){
                                continue;   
                            }
                            echo $e->getMessage();
                            echo '<br>Linha:</b> '.$linha;
                            //print("<br><br>TOKENS RECONHECIDOS ANTES DO ERRO:<br>");
                            //var_dump($this->lista_tokens);
                            //print('<br><br>Erro léxico!<br>');
                            exit;
                            //echo "<br>Posição: ".$posicao;
                        }
                    }  
                }
                if(array_key_exists($estado,  $this->finais)){
                    //require_once('Token.php');
                    $this->tk = new Token($this->finais[$estado], $lexema,$posicao,$linha);
                    if(!($estado == '56' | $estado == '57' )){
                        array_push($this->lista_tokens, $this->tk);
                        array_push($this->somente_tokens,$this->finais[$estado]);
                    }
                    
                }
                
            }//lexicoAcao+
              
           function mostraListaTokens(){
                print_r("<br><br><h4>Lista de tokens reconhecidos: </h4>");
                //var_dump($this->lista_tokens);
            }

            function next_token(){
              

            }
                       
        } //classLexico

       $entrada = isset($_POST['entrada']) ? $_POST['entrada'] : "";

        $lex = new Lexico($entrada);

        $lex->__construct($entrada);
        $lex->next_token();
        //$lex->mostraListaTokens();
                
        ?>

