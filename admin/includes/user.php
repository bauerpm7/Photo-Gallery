<?php 


class User {

  public $id;
  public $username;
  public $first_name;
  public $last_name;
  public $password;


  public static function find_all_users() {
    return self::find_this_query("SELECT * FROM users");
  }

  public static function find_user_by_id($user_id) {
    global $database;
    $result_array = self::find_this_query("SELECT * FROM users WHERE id = $user_id LIMIT 1" );
    return !empty($result_array) ?  array_shift($result_array) : false;
  }

  public static function find_this_query($sql){
    global $database; 
    $result_set = $database->query($sql);
    $the_object_array = array();
    while($row = mysqli_fetch_array($result_set)){
      $the_object_array[] = self::instantiate_user($row);
    }
    return $the_object_array;
  }

  public static function verify_user($username, $password) {
    global $database;
    $username = $database->escape_string($username);
    $password = $database->escape_string($password);
    $sql = "SELECT * FROM users WHERE ";
    $sql .= "username = '{$username}' ";
    $sql .= "AND password = '{$password}' ";
    $sql .= "LIMIT 1";
    $result_array = self::find_this_query($sql);
    return !empty($result_array) ? array_shift($result_array) : false;
  }

  public static function instantiate_user($the_record) {
    $the_object = new self();
    foreach ($the_record as $property => $value) {
      if($the_object->has_the_property($property)){
        $the_object->$property = $value;
      }
    }
    return $the_object;
  }

  private function has_the_property($the_property) {
    $object_properties = get_object_vars($this);
    return array_key_exists($the_property, $object_properties);
  }

  public function save() {
    return isset($this->id) ? $this->update() : $this->create();
  }

  public function create() {
    global $database;

    $sql = "INSERT INTO users (username, password, first_name, last_name)";
    $sql .= "Values ('";
    $sql .= $database->escape_string($this->username) ."','";
    $sql .= $database->escape_string($this->password) ."','";
    $sql .= $database->escape_string($this->first_name) ."','";
    $sql .= $database->escape_string($this->last_name) ."')";

    if($database->query($sql)){
      $this->id = $database->insert_id();
      return true;
    } else {
      return false;
    }
  } //create method

  public function update(){
    global $database;

    $sql = "UPDATE users SET ";
    $sql .= "username= '"   .$database->escape_string($this->username)    ."',";
    $sql .= "password= '"   . $database->escape_string($this->password)   ."',";
    $sql .= "first_name= '" . $database->escape_string($this->first_name) ."',";
    $sql .= "last_name= '"  . $database->escape_string($this->last_name)  ."' ";
    $sql .= "WHERE id= "   . $database->escape_string($this->id);

    $database->query($sql);
    return (mysqli_affected_rows($database->connection) == 1) ? true : false;
  } //update method

  public function delete_user () {
    global $database;

    $sql = "DELETE FROM users WHERE id= " . $database->escape_string($this->id);
    $sql .= " LIMIT 1";
    $database->query($sql);
    return (mysqli_affected_rows($database->connection) == 1) ? true : false;
  }





} //end of user class


?>