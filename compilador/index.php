<?php
require_once('../analisadorLexico./AnalisadorLexicoClasse.php');
require_once('../analisadorAscendente./AnalisadorAscendenteSLR.php');
$entrada = isset($_POST['entrada']) ? $_POST['entrada'] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1><title>Compilador</title></h1>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        .container { 
        width: 500px; 
        margin-left: auto;
        margin-right: auto; 
    }
    .vertical {
            border-left: 6px solid black;
            height: 600px;
            position:absolute;
            left: 50%;
        }
  </style>
</head>
    <body>
        <div class="container">

            <form action= "" method="POST">
                <div class="form-group text-center">
                    <label for="sentenca" class="form-label"><h3><b>Analisador Ascendente SLR</b></h3></label><br><br>
                    <label for ="entrada"></label>
                    <textarea name="entrada" id="entrada"><?=$entrada?></textarea><br><br>
                    <button class="btn btn-primary" type="submit">Gerar código Assembly</button><br><br>
                </div> 

<?php

    class Index{

        private $analisadorAscendenteSLR;

        function __construct(AnalisadorAscendenteSLR $analisadorAscendenteSLR)
        {
            $this->analisadorAscendenteSLR = $analisadorAscendenteSLR;
        }

        function start($entrada){
            if($this->analisadorAscendenteSLR->analiseSLR($entrada)){
                echo "SIMMM";
            }
        }
    }//index

    $lexico = new Lexico($entrada.'#');
    if(isset($_POST['entrada'])){  
        $SLR  = new AnalisadorAscendenteSLR($lexico,$entrada);
    }
    $index = new Index($SLR);

    var_dump($index->start($entrada));
        
    /*echo "<br><br><br>";
    if(!empty($SLR->programa)){
       print_r($SLR->programa);
    }*/


?>

        </div>

    </body>
        </form>
</html>