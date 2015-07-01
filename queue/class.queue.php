<?php

  class queue{
    private $top;
    private $queue;

    public function __construct(){
      $this->top = 0;
      $this->queue = array();
    }

    public function push($x){
      array_push($this->queue, $x);
      $this->top++;
    }

    public function pop(){
      $val = $this->queue[0];
      array_splice($this->queue, 0, 1);
      $this->top--;
      return $val;
    }

    public function empty_queue(){
      for($i = 0; $i < $this->top; $i++){
        $this->pop();
      }
    }

    public function print_queue(){
      echo print_r($this->queue,true);
    }
  }