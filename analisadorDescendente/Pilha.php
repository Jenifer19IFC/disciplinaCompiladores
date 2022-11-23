<?php

class Stack
{
    protected $stack;
    protected $limit;

    public function __construct($limit = 500) {
        // initialize the stack
        $this->stack = array();
        // stack can only contain this many items
        $this->limit = $limit;  
    }

    public function push($item) {
        // trap for stack overflow
        if (count($this->stack) < $this->limit) {
            // prepend item to the start of the array
            array_unshift($this->stack, $item);
        } else {
            throw new RunTimeException('Pilha está cheia');
        }
    }

    public function pop() {
        if ($this->isEmpty()) {
            // trap for stack underflow
          throw new RunTimeException('Pilha está vazia');
      } else {
            // pop item from the start of the array
            return array_shift($this->stack);
        }
    }

    public function top() {
        return current($this->stack);
    }

    public function isEmpty() {
        return empty($this->stack);
    }
}

$stack = new Stack();
//$stack->push(1);
//$stack->push(2);
//$stack->push(3);

//echo "<br><b>Pilha inicial:</b><br>";
//var_dump($stack);
//echo '<br><b>Elemento removido:</b> '.$stack->pop();
//echo "<br><b>Após remoção:</b><br>";
//var_dump($stack);


?>	