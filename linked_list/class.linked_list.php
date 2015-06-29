<?php

  class ll{

    private $first;
    private $last;
    private $count;
    private $print_counter;
    private $is_html;

    public function __construct($is_html=false){
      $this->first = null;
      $this->last = null;
      $this->count = 0;
      $this->is_html = $is_html;
    }

    public function push($data){
      if($this->first === null && $this->last === null){
        $node = new node($data);
        $node->set_prev($node);
        $node->set_next($node);
        $this->first = &$node;
        $this->last = &$node;
        $this->count++;
      } 
      else {
        $node = new node($data);
        $this->first->set_prev($node);
        $node->set_next($this->first);
        $node->set_prev($this->last);
        $this->first = &$node;
        $this->count++;
      }
    }

    public function pop(){
      //this assumes we are popping the top node off
      if($this->first !== null){
        if($this->count == 1){
          $val = $this->first->get_data();
          $this->first = null;
          $this->last = null;
          $this->count = 0;
          return $val;
        }
        else if($this->count > 1) {
          $val = $this->first->get_data();
          $this->first = $this->first->get_next();
          $this->first->set_prev($this->last);
          $this->count--;
          return $val;
        }
        else {
          return null; // empty list
        }
      }
    }

    public function get_count(){
      return $this->count;
    }

    public function peek($index){
      return $this->first->get_data();
    }

    public function empty_list(){
      $max = $this->count;
      for($i = 0; $i < $max; $i++){
        $this->pop();
      }
    }

    public function print_list(){
      $this->print_counter = 1;
      if($this->first !== null && $this->count > 0){
        $this->_print($this->first);
      }
      else{
        echo "List is empty!";
        if($this->is_html)
          echo "<br/>";
        else
          echo "\n";
      }
    }

    private function _print($node){
      echo $this->print_counter++ . ") " . $node->get_data() . " (unique id: ".$node->get_unique_id().")";
      if($this->is_html)
        echo "<br/>";
      else
        echo "\n";

      if($node->get_next() !== null && $this->print_counter <= $this->count){
        $this->_print($node->get_next());
      }
    }
  }// end linked list class implementation


  class node{
    private $unique_id;
    private $next;
    private $prev;
    private $data;

    public function __construct($d){
      $this->prev = null;
      $this->next = null;
      $this->data = $d;
      $this->unique_id = md5( $d );
    }

    public function set_next(&$n){
      $this->next = &$n;
    }

    public function set_prev(&$p){
      $this->prev = &$p;
    }

    public function get_next(){
      return $this->next;
    }

    public function get_prev(){
      return $this->prev;
    }

    public function get_data(){
      return $this->data;
    }

    public function get_unique_id(){
      return $this->unique_id;
    }
  }