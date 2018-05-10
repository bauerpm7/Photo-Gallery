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

    return !empty($result_array) ? $first_item = array_shift($result_array) : false;
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
}


?>