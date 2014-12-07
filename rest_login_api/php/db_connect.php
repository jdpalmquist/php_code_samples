<?php

  //pdo wrapper

  class db {

    private $config;
    private $pdo;
    private $pso;
    private $conn;
    private $result;
    public  $error_msg;
    public  $error_code;
    public  $state;

    function __construct(){
      $this->config = array();
      $this->pdo = null;
      $this->pso = null;
      $this->conn = null;
      $this->result = null;
      $this->error_msg = '';
      $this->error_code = 0;
      $this->state = 'Database Object Initialized';
    }

    public function set_config($config=null){
      if($config !== null){
        $this->config["db_name"] = $config["db_name"];
        $this->config["host_ip"] = $config["host_ip"];
        $this->config["port_num"] = $config["port_num"];
        $this->config["username"] = $config["username"];
        $this->config["password"] = $config["password"];
      }
    }

    private function get_config(){
      return $this->config;
    }

    public function connect(){
      $db = $this->get_config();
      $db_name = $db["db_name"];
      $host_ip = $db["host_ip"];
      $port = $db["port_num"];
      $username = $db["username"];
      $password = $db["password"];

      if( $host_ip != null && 
          $username != null )
      {
        try
        {
          //whether or not to use port number
          if($port != null)
            $this->conn = "mysql:dbname=$db_name;host=$host_ip;port=$port;";
          else
            $this->conn = "mysql:dbname=$db_name;host=$host_ip;"; //port num defaults to 3306

          //Creates the actual connection:
          $this->pdo = new PDO($this->conn, $username, $password);//, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
          
          //update our state message
          if($this->is_connected())
            $this->state = "Connected to the database";
          else
            $this->state = "Not connected";
        }
        catch(PDOException $e)
        {
          $this->pdo = null;
          $this->pso = null;
          $this->result = null;
          $this->error_code = $e->getCode();
          $this->error_msg = $e->getMessage();
          $this->state = "Exception occurred during PDO connection!";
        }
      }
      else
      {
        $this->pdo = null;
        $this->pso = null;
        $this->result = null;
        $this->error_code = false;
        $this->error_msg = "";

        if($db_name == null)
          $this->error_msg .= " Database name was missing! ";

        if($host_ip == null)
          $this->error_msg .= " MySQL Server IP was missing! ";

        if($username == null)
          $this->error_msg .= " Username was missing! ";

        if($password == null)
          $this->error_msg .= " Password was missing! ";

        $this->state = "Not connected";
      }

      //regardless of success or failure return the current connection status
      return $this->is_connected();
    }

    public function is_connected(){
      if($this->pdo != null)
        return true;
      else
        return false;
    }

    public function query($sql, $params=null){
      
      if(!$this->is_connected()){
        $this->state = "Not Connected to the database";
        return false;
      }

      //Perform a secure query
      if($sql != null && $params != null)
      {
        try
        {
          $this->pso = $this->pdo->prepare($sql);
          if($this->pso !== false)
          {
            $this->pso->execute($params);
            $this->result = $this->pso->fetchAll(); // return results as array
            $this->state = "Connected to the database"; // generic "system ok" message
            return $this->result;
          }
          else
          {
            return false;
          }
        }
        catch(PDOException $e)
        {
          $this->error_code = $e->getCode;
          $this->error_msg = $e->getMessage();
          $this->state = "Exception occurred";
        }
        return false;
      }
      else if($sql != null && $params == null) //insecure query 
      {
        $this->pso = $this->pdo->query($sql); // this is NOT a prepared statement
        if($this->pso !== false)
        {
          $this->result = $this->pso->fetchAll(); // return results as array
          $this->state = "Connected to the database"; // generic "system ok" message
          return $this->result;
        }
        else
        {
          $this->state = "Query failed to return a PSO object";
          return false;
        }
      }
      else
      {
        $this->state = "Missing SQL Statement";
        return false;
      }
    }

    public function execute(){

    } 

  }