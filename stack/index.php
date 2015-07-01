<?php

  require_once 'class.stack.php';

  $stack = new stack();

  $stack->push('a');
  $stack->push('b');
  $stack->push('c');

  $stack->print_stack();

  $stack->pop();

  $stack->print_stack();

  $stack->pop();

  $stack->print_stack();

  $stack->pop();
