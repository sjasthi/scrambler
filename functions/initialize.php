<?php
  ob_start(); // output buffering is turned on
  if(session_id() == '' || !isset($_SESSION)){
    session_start();
  }
  
  
  // Assign file paths to PHP constants
  // __FILE__ returns the current path to this file
  // dirname() returns the path to the parent directory
  define("PRIVATE_PATH", dirname(__FILE__));		
  define("PROJECT_PATH", dirname(PRIVATE_PATH));   
  define("PUBLIC_PATH", PROJECT_PATH . '/public');	
  define("SHARED_PATH", PRIVATE_PATH . '/shared');
  //define("ROOT_PATH", 'C:\xampp\htdocs\scrambler2\\');
  
  //--------------------------------  Changes these two lines tocorrectly reflect the deployment details 
  define("DEPLOYMENT_URL", "http://safe");
  define("SERVER_ROOT_PATH", "htp://safe");
  //--------------------------------------------------------------------------------------
  
  // Assign the root URL to a PHP constant
  $public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
  $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
  define("WWW_ROOT", $doc_root);
  if(strcmp($nav_selected, 'HOME') == 0) {
    require_once('functions.php');
    require_once('./db/database.php');
    require_once('query_functions.php');
    require_once('./validation_functions.php');
  } else {
    require_once('functions.php');
    require_once('../db/database.php');
    require_once('query_functions.php');
    require_once('../validation_functions.php');
  }

  $db = db_connect();
  $errors = array();
  $config = array();
  
  define("DEBUG_MODE", "ON");
  
?>
