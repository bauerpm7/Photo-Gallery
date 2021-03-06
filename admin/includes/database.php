<?php 

require_once("new_config.php");

class Database {

  public $connection;

//automatically calls the open_db_connection method
  function __construct(){
    $this->open_db_connection();
  }

//opens connection to the mysql database
  public function open_db_connection() {
    $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if($this->connection->connect_errno) {
      die('Database connection failed' . $this->connection->connect_errno);
    } 
  }

//helper function to use with all query methods
  public function query($sql){
    $result = $this->connection->query($sql);
    $this->confirm_query($result);
    return $result;
  }

//terminates the query if no results are returned
  private function confirm_query($result){
    if(!$result){
      die('Query Failed' . $this->connection->error);
    }
  }

//escapes the string
  public function escape_string($string) {
    $escaped_string = $this->connection->real_escape_string($string);
    return$escaped_string;
  }


  public function insert_id() {
    return mysqli_insert_id ($this->connection);
  }

}


$database = new Database();

 ?>