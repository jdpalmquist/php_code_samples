<?php

  //locallogin.php

  //define the name of the RDBM SQL database where locallogin is stored  
  define('DATABASENAME', 'databasename');
  //define the name of the table where the locallogin data is stored
  define('TABLENAME', 'tablename');

  //
  require_once 'db_connect.php';

  /**
   * Local Login
   * the username + password data will be stored in the $_POST array
   *
   */
  function login($username, $password){

    $db = new db();

    $db->set_config(array(
      'db_name' => 'mysql',
      'host_ip' => 'localhost',
      'port_num' => '3306',
      'username' => 'root',
      'password' => '$_d@rknight55'
    ));

    $db->connect();

    //step 1: perform select for the username
    echo "Result: " . print_r($db->query("show tables;"), true) . "\n\n";

    echo "State: " . $db->state . "\n\n";

    //step 2: 

  }

  login('username', 'password');