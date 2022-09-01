<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1><title>Compilador</title></h1>
</head>
    <body>
        <form action= "" method="POST">
            <fieldset>
            <?php
                print("<h4>COMPILADOR</h4>
                Escreva a sentença abaixo:<br><br>");
            ?>
                <label for ="entrada"></label>
                    <textarea name="entrada" id="entrada"></textarea><br>
                    <input type="submit" value="Enviar">  

        <?php

            require_once('AnalisadorLexicoClasse.php');
            require_once('AnalisadorSintaticoClasse.php');

            $entrada = isset($_POST['entrada']) ? $_POST['entrada'] : "";
            $lexico = new Lexico($entrada);

            print('<br><br>Entrada digitada: <br>'.$entrada.'<br>');

            $sintatico = new Sintatico($lexico);
            echo "<br>";

            $resultado = $sintatico->P();
            
            if($resultado == true){
                print('<br>Linguagem aceita!');
            }else{
                print('<br>Erro sintático!');
            }
            
                
        ?>

            </body>
        </fieldset>
    </form>
</html>