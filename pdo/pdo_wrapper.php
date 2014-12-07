<?php

define("DATABASE_ERROR", -1);
define("DEV_MODE", 1);
define("PRD_MODE", 2);
define("__MODE__", DEV_MODE); // for easy switching upon deployment


class database
{
  static private $pdo;
  static private $pso;
  static private $conn;
  static private $result;
  static public  $error_msg;
  static public  $error_code;
  static public  $state;
  
  static private function get_config()
  {
    $db = array();
    switch(__MODE__)
    {
      case DEV_MODE:
        $db["db_name"]  = "<database name here>";
        $db["host_ip"]  = "localhost";
        $db["port_num"] = "3306";
        $db["username"] = "<username here>";
        $db["password"] = "<password here>";
        break;

      case PRD_MODE:
        $db["db_name"]  = "<database name here>";
        $db["host_ip"]  = "localhost";
        $db["port_num"] = "3306";
        $db["username"] = "<username here>";
        $db["password"] = "<password here>";
        break;

      default: break;
    }
    return $db;
  }//end method database::get_config()

  static private function is_connected()
  {
    if(database::$pdo != null)
      return true;
    else
      return false;
  }//end method database::is_connected()

  static private function connect()
  {   
    $db = database::get_config();
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
          database::$conn = "mysql:dbname=$db_name;host=$host_ip;port=$port;";
        else
          database::$conn = "mysql:dbname=$db_name;host=$host_ip;"; //port num defaults to 3306

        //Creates the actual connection:
        database::$pdo = new PDO(database::$conn, $username, $password);//, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        
        //update our state message
        if(database::is_connected())
          database::$state = "Connected to the database";
        else
          database::$state = "Not connected";
      }
      catch(PDOException $e)
      {
        database::$pdo = null;
        database::$pso = null;
        database::$result = null;
        database::$error_code = $e->getCode();
        database::$error_msg = $e->getMessage();
        database::$state = "Exception occurred";
      }
    }
    else
    {
      database::$pdo = null;
      database::$pso = null;
      database::$result = null;
      database::$error_code = DATABASE_ERROR;
      database::$error_msg = "";

      if($db_name == null)
        database::$error_msg .= " Database name was missing! ";

      if($host_ip == null)
        database::$error_msg .= " MySQL Server IP was missing! ";

      if($username == null)
        database::$error_msg .= " Username was missing! ";

      if($password == null)
        database::$error_msg .= " Password was missing! ";

      database::$state = "Not connected";
    }

    //regardless of success or failure return the current connection status
    return database::is_connected();
  }//end method database::connect()

  static private function autoconnect()
  {
    if(database::is_connected())
      return true;
    else
      return database::connect();
  }//end method database::autoconnect()

  static public function query($sql=null, $params=null)
  {   
    if(database::autoconnect())
      return database::_query($sql, $params);
    else
      return DATABASE_ERROR;
  }//end method database::query()

  //Note: this function is for performing SELECT
  //Note: this function will return a sql result set in the form of an array or DATABASE_ERROR on failure
  static private function _query($sql=null, $params=null)
  {
    //Perform a secure query
    if($sql != null && $params != null)
    {
      try
      {
        database::$pso = database::$pdo->prepare($sql);
        if(database::$pso !== false)
        {
          database::$pso->execute($params);
          database::$result = database::$pso->fetchAll(); // return results as array
          database::$state = "Connected to the database"; // generic "system ok" message
          return database::$result;
        }
        else
        {
          return DATABASE_ERROR;
        }
      }
      catch(PDOException $e)
      {
        database::$error_code = $e->getCode;
        database::$error_msg = $e->getMessage();
        database::$state = "Exception occurred";
      }
      return DATABASE_ERROR;
    }
    else if($sql != null && $params == null) //insecure query 
    {
      database::$pso = database::$pdo->query($sql); // this is NOT a prepared statement
      if(database::$pso !== false)
      {
        database::$result = database::$pso->fetchAll(); // return results as array
        database::$state = "Connected to the database"; // generic "system ok" message
        return database::$result;
      }
      else
      {
        database::$state = "Query failed to return a PSO object";
        return DATABASE_ERROR;
      }
    }
    else
    {
      database::$state = "Missing SQL Statement";
      return DATABASE_ERROR;
    }
  }//end method database::_query()

  //Note: this function is for performing INSERT, UPDATE, DELETE 
  //Note: this function returns the number of rows affected or DATABASE_ERROR on failure
  static public function execute($sql=null, $params=null)
  {   
    if(database::autoconnect())
      return database::_execute($sql, $params);
    else
      return DATABASE_ERROR;
  }//end method database::execute()

  static private function _execute($sql=null, $params=null)
  {   
    if($sql != null && $params != null) //secure operation
    {
      database::$pso = database::$pdo->prepare($sql);
      if(database::$pso !== false)
      {
        database::$pso->execute($params);
        database::$result = database::$pso->rowCount(); //number of rows affected
        database::$state = "Connected to the database";
        return database::$result;
      }
      else
      {
        database::$state = "Query failed to return a PSO object";
        return DATABASE_ERROR;
      }     
    }
    else if($sql != null && $params == null) // insecure operation... not a prepared statement
    {
      database::$result = database::$pdo->exec($sql);
      database::$state = "Connected to the database";
      return database::$result;
    }
    else
    {
      database::$state = "Missing SQL Statement";
      return DATABASE_ERROR;
    }
  }//end private method database::_execute()

}//end database class