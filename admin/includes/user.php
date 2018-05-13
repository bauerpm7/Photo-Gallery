<?php 


class User {

  protected static $db_table = "users";
  protected static $db_table_fields = array(
    'username',
    'password',
    'first_name',
    'last_name'
  );
  public $id;
  public $username;
  public $first_name;
  public $last_name;
  public $password;


  public static function find_all_users() {
    return self::find_this_query("SELECT * FROM " .self::$db_table);
  }//find_all_users

  public static function find_user_by_id($user_id) {
    global $database;
    $result_array = self::find_this_query("SELECT * FROM " .self::$db_table ." WHERE id = $user_id LIMIT 1" );
    return !empty($result_array) ?  array_shift($result_array) : false;
  }//find_user_by_id

  public static function find_this_query($sql){
    global $database; 
    $result_set = $database->query($sql);
    $the_object_array = array();
    while($row = mysqli_fetch_array($result_set)){
      $the_object_array[] = self::instantiate_user($row);
    }
    return $the_object_array;
  }//find_this_query

  public static function verify_user($username, $password) {
    global $database;
    $username = $database->escape_string($username);
    $password = $database->escape_string($password);
    $sql = "SELECT * FROM " .self::$db_table . " WHERE ";
    $sql .= "username = '{$username}' ";
    $sql .= "AND password = '{$password}' ";
    $sql .= "LIMIT 1";
    $result_array = self::find_this_query($sql);
    return !empty($result_array) ? array_shift($result_array) : false;
  } //verify users

  public static function instantiate_user($the_record) {
    $the_object = new self();
    foreach ($the_record as $property => $value) {
      if($the_object->has_the_property($property)){
        $the_object->$property = $value;
      }
    }
    return $the_object;
  }//instantiate_user

  private function has_the_property($the_property) {
    $object_properties = get_object_vars($this);
    return array_key_exists($the_property, $object_properties);
  }//has_the_property

  protected function properties() {
    $properties = array();

    foreach (self::$db_table_fields as $db_field) {
      if(property_exists($this, $db_field)){
        $properties[$db_field] = $this->$db_field;
      }
    }
    return $properties;
  }//properties

  public function save() {
    return isset($this->id) ? $this->update() : $this->create();
  }//save

  public function create() {
    global $database;
    $properties = $this->properties();
    $sql = "INSERT INTO " .self::$db_table . "(" . implode(",", array_keys($properties)) . ")";
    $sql .= "Values ('" . implode("','", array_values($properties)) . "')";
    if($database->query($sql)){
      $this->id = $database->insert_id();
      return true;
    } else { return false; }
  } //create method

  public function update(){
    global $database;
    $properties = $this->properties();
    $property_pairs = array();
    foreach ($properties as $key => $value){
      $property_pairs[] = "{$key} = '{$value}'";
    }
    $sql = "UPDATE " .self::$db_table . " SET ";
    $sql .= implode(", ", $property_pairs);
    $sql .= " WHERE id= "   . $database->escape_string($this->id);
    $database->query($sql);
    return (mysqli_affected_rows($database->connection) == 1) ? true : false;
  } //update method

  public function delete_user () {
    global $database;

    $sql = "DELETE FROM " .self::$db_table . " WHERE id= " . $database->escape_string($this->id);
    $sql .= " LIMIT 1";
    $database->query($sql);
    return (mysqli_affected_rows($database->connection) == 1) ? true : false;
  }





} //end of user class


?>