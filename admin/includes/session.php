<?php 

class Session {

  private $signed_in = false;
  public $user_id;
  public $message;

  //start the users session
  function __construct() {
    session_start();
    $this->check_login();
    $this->check_message();
  }


  public function message($msg=""){
    if(!empty($msg)){
      $_SESSION['message'] = $msg;
    } else {
      return $this->message;
    }
  }

  private function check_message(){
    if(isset($_SESSION['message'])){
      $this->message = $_SESSION['message'];
      unset($_SESSION['message']);
    } else {
      $this->message = "";
    }
  }

  //getter function to use throughout the application to show if user is signed in
  public function is_signed_in() {
    return $this->signed_in;

  }

  //log the user in 
  public function login($user) {
    if($user) {
      $this->user_id = $_SESSION['user_id'] = $user->id;
      $this->signed_in = true;
    }
  }

  //log the user out
  public function logout() {
    unset($this->user_id);
    unset($_SESSION['user_id']);
    $this->signed_in = false;
  }

  //check if the session userid is set if it is set $signed_in to true
  //else set $signed_in to false
  private function check_login() {
    if(isset($_SESSION['user_id'])){
      $this->user_id = $_SESSION['user_id'];
      $this->signed_in = true;
    } else {
      unset($this->user_id);
      $this->signed_in = false;
    }
  }
}

//instantiate the Session
$session = new Session();


?>