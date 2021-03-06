<?php

  class stack{
    private $top;
    private $stack;

    public function __construct(){
      $this->top = 0;
      $this->stack = array();
    }

    public function push($x){
      array_push($this->stack, $x);
      $this->top++;
    }

    public function pop(){
      $val = $this->stack[($this->top - 1)];
      array_splice($this->stack, (--$this->top), 1);
      return $val;
    }

    public function empty_queue(){
      for($i = 0; $i < $this->top; $i++){
        $this->pop();
      }
    }

    public function print_stack(){
      echo print_r($this->stack,true);
    }
  }