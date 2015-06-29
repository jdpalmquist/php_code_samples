<?php

  //echo "Testing module for the linked list<br/>\n";

  require_once 'class.linked_list.php';

  $list = new ll();

  $list->push("a");
  $list->push("b");
  $list->push("c");
  $list->push("d");
  $list->push("e");
  $list->push("f");

  echo "Current count: " . $list->get_count() . "\n";

  echo "Printing the List: \n";

  $list->print_list();

  $list->empty_list();

  $list->print_list();