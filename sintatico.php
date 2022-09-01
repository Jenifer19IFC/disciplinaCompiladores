<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title>Analisador Sintático</title>
    <meta charset="utf-8">
  </head>
  <body> 
   <?php
    
/*class Sintatico{

    public $cont = 0;
    public $anterior = 0;
    public $lista_de_tokens = array(
        ['token' => 'AP'],
        ['token' => 'INT'],
        ['token' => 'FP']
    );

    function term($token){
        if($this->cont >= count($this->lista_de_tokens)){
            return false;
        }
        $ret =  $this->lista_de_tokens[$this->cont]['token'] == $token;
        $this->cont  = $this->cont + 1;
        return $ret;
    }

    function E1(){
        return $this->T();
    }

    function E2(){
       return $this->T() and $this->term('MAIS') and $this->E();    
    }

    function E(){
       $this->anterior = $this->cont;
        if($this->E1()){
            return true;
        }else{
            $this->cont =  $this->anterior;
            return $this->E2();
        }
    }
    
    function T1(){
        return $this->term('INT');
    }

    function T2(){
        return $this->term('INT') and $this->term('MULT') and $this->T();
    }

    function T3(){
        return $this->term('AP') and $this->E() and $this->term("FP");
    }

    function T(){
        $this->anterior = $this->cont;
        if ($this->T1()){
            return True;
        }else{
            $this->cont  =  $this->anterior ;
            if ($this->T2()){
                return True;
            } else{
                $this->cont =  $this->anterior ;
                return $this->T3();
            }
        }
       
    }
}

$sin = new Sintatico();

$resultado = $sin->E();
while($resultado && $sin->cont < count($sin->lista_de_tokens)){
    $resultado = $sin->E();
}
if($resultado == false)
    echo "0";
else
    echo $resultado;


?>

  </body>
</html>*/