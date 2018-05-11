<?php 

class Session {

  private $signed_in = false;
  public $user_id;

  //start the users session
  function __construct() {
    session_start();
    $this->check_login();
  }

  //getter function to use throughout the application to show if user is signed in
  public function is_signed_in() {
    return $this->$signed_in;
  }

  public function login($user) {

    if($user) {
      $this->user_id = $_SESSION['user_id'] = $user->id;
      $this->$signed_in = true;
    }

  }

  //check if the session userid is set if it is set $signed_in to true
  //else set $signed_in to false
  private function check_login() {

    if(isset($_SESSION['user_id'])){
      $this->user_id = $_SESSION['user_id'];
      $this->signed_in = true
    }else {
      unset($this->user_id);
      $this->$signed_in = false;
    }
  }




}

//instantiate the Session
$session = new Session();





 ?>