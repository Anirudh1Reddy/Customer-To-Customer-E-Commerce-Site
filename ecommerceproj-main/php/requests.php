<?php
// ! command to generate documentation : docker run --rm -v "$(pwd):/data" "phpdoc/phpdoc:3" -t ./phpdoc

// Start session --------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


// Global variables ------------------------------------------------------------------
$request = isset($_POST['request']) ? $_POST['request'] : null;


// Connect to DB ---------------------------------------------------------------------
include_once 'dbh_inc.php';


// Responder --------------------------------------------------------------------------
include_once 'classes.php';
$responder = new Responder;
$dbObj = new Database;

// Main -------------------------------------------------------------------------------
switch($request){
  case "add_to_cart":
    $responder->add_to_cart();
  break;

  case "remove_from_cart":
    $responder->remove_from_cart();
  break;

  case "init_session":
    $responder->init_session();
  break;

  case "delete_session":
    $responder->destroy_session();
  break;

  case "sign_in":
    $data = $responder->sign_in($conn);
  break;

  case "sign_up":
    $data = $responder->sign_up($conn);
  break;

  case "checkout":
    $responder->checkout($dbObj, $conn);
  break;

  default:
    $status = false;
    $message = "There is no such request";

    $responder->printJSON(array(
      'status' => $status,
      'message' => $message
    ));
    break;
}