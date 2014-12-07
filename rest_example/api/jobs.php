<?php

  // jobs.php
  // A REST API endpoint for submitting and querying available jobs

  //POST search() : searches for jobs, accepts parameters, defaults to no params 
  //POST create() : creates a new job posting, request must be logged in
  //POST edit()   : edits an existing post, must be logged in
  //POST remove() : removes an existing post, must be logged in
  //POST login()  : user login endpoint
  //POST logout() : user logout endpoint

  //start by checking for flags
  if(isset($_POST['payload'])){

    //All json data should be sent POST, 
    //  json data should be embedded into keyvalue "payload"
    $payload = json_decode($_POST['payload']);

    //each payload should contain a top level flag "type"
    //  and each payload should contain a top level "data" object
    
    //get the data from the data object
    $data = $payload["data"];
    
    //switch by api request type
    switch($payload["type"]){
      case "search":
        jobsapi::search($data);
      break;

      case "create":
        jobsapi::create($data);
      break;

      case "edit":
        jobsapi::edit($data);
      break;

      case "remove":
        jobsapi::remove($data);
      break;

      case "login":
        jobsapi::login($data);
      break;

      case "logout":
        jobsapi::logout($data);
      break;

      default:
        jobsapi::error($data);
      break;
    }
  } //end check for flags, no "else" just abort script if no request

//static method container class to hold the search api functions.
class jobsapi{

  static public function create($data=null){
    //$data should be an array with the following key/value job data
    /*
      Title
      Keywords
      Body
      
      * all other associated info is pulled from company profile
    */


  }//end create()

  static public function search($data=null){


  }// end search()

  static public function edit($data=null){

  }//end edit()

  static public function remove($data=null){

  }//end remove()

  static public function error($data=null){

  }//end error()


  static public function login($data=null){

  }

  static public function logout($data=null){

  }

  static private function isloggedin(){
    if(isset($_SESSION["userstatus"]) && $_SESSION["userstatus"] === 'loggedin')
      return true;
    else 
      return false;
  }
  
  static private function query($query, $params=array()){
    if($query !== null && $query !== ""){

      //dogfooding: using my own database wrapper class in another project!
      require_once "../../pdo/pdo_wrapper.php";

      

    }
  }

  static private function execute($query, $params=array()){

  }
}//end jobsapi 