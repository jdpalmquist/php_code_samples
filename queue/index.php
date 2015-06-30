<?php

  require_once 'class.queue.php';

  $q = new queue();

  $q->push('a');
  $q->push('b');
  $q->push('c');

  $q->print_queue();