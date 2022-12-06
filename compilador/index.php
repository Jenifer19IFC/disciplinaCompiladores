<?php
require_once('../analisadorAscendente./AnalisadorAscendenteSLR.php');
require_once('../geracaoDeCodigo./GeradorDeCodigo.php');
$entrada = isset($_POST['entrada']) ? $_POST['entrada'] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1><title>Compilador SLR</title></h1>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        .container { 
        width: 500px; 
        margin-left: auto;
        margin-right: auto; 
        width: 68rem;
        align-self: center;
        text-align: center;

    }
    .vertical {
            border-left: 6px solid black;
            height: 600px;
            position:absolute;
            left: 50%;
        }
    p {
        font-family: 'Courier New';font-size: 20px;
    }
    body{
        color: #000000;
        font-family: 'Microsoft Sans Serif';font-size: 20px;
        background-color: light;

    } 
    .code{
        width: 50;
        color: #000000; 
        border-radius: 5%;
        font-family: 'Courier New';
        font-size: 20px;
       
        opacity: 80%;
        margin-right: 10%;
        margin-left: 35%;
        
    }
    .textAreaSentence{
        color: #000000;
        font-family: 'Microsoft Sans Serif';
        font-size: 20px;
    }
    .title{
        color: #000000;
        font-family: 'Microsoft Sans Serif';
        font-size: 20px;  
    }
   
  </style>
</head>
    <body>
        <div class="container">
            <form action= "" method="POST">
                <div class="form-group text-center">
                    <div class="title">
                        <label for="sentenca" class="form-label"><h2><b><hr>Analisador Ascendente SLR<hr></b></h2></label><br>
                    </div>
                    <div class="textAreaSentence">
                        <label for ="entrada"></label>
                        <h3><textarea name="entrada" id="entrada" cols="40" rows="7"><?=$entrada?></textarea></h3><br>
                    </div>
                    <button class="btn btn-primary" type="submit"><h4>Gerar código Assembly</h4></button><br>    
                </div> 

                
                    <?php
                        $lexico = new Lexico($entrada.'#');
                        if(isset($_POST['entrada'])){  
                            $SLR  = new AnalisadorAscendenteSLR($lexico,$entrada);  ?>
                          <?php  if($SLR->aceita == true){ ?>
                                <div class="form-group text-center">
                                    <br><label for="accept" class="form-label"><h4><b>Linguagem aceita</b></h4></label>    
                                    <img src="../img./certo32px.png" alt=''>
                                    <hr>
                                </div>
                                 <?php $geradorCodigo = new GeradorCodigo();
                                    $codigoEmAssembly = $geradorCodigo->geraCodigoAssembly($SLR->programa); ?>
                                    <div><h5><b> 
                                        <div class="code" >
                                            <div class="form-group">
                                                    <p class="text-left">
                                                        <?php echo $codigoEmAssembly; ?>
                                                    </b></h5>
                                                    </p>    
                                            </div>
                                        </div>
                                    </div>
                                    
                            <?php }else{ 
                                if(!$_POST['entrada'] == ""){?>
                                <div class="form-group text-center">
                                    <br><label for="accept" class="form-label"><h4><b>Linguagem incorreta</b></h4></label>    
                                    <img src="../img./errado32px.png" alt=''>
                                </div>
                                <?php }
                
                            }
                        }
                    ?>

        </div>
        </form>

    </body>
        
</html>