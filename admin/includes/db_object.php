<?php 

class Db_object{

  public static function find_all() {
    return self::find_this_query("SELECT * FROM " .self::$db_table);
  }//find_all

  public static function find_by_id($id) {
    global $database;
    $result_array = self::find_this_query("SELECT * FROM " .self::$db_table ." WHERE id = &id LIMIT 1" );
    return !empty($result_array) ?  array_shift($result_array) : false;
  }//find_by_id

  public static function find_this_query($sql){
    global $database; 
    $result_set = $database->query($sql);
    $the_object_array = array();
    while($row = mysqli_fetch_array($result_set)){
      $the_object_array[] = self::instantiate($row);
    }
    return $the_object_array;
  }//find_this_query

  public static function instantiate($the_record) {
    $the_object = new self();
    foreach ($the_record as $property => $value) {
      if($the_object->has_the_property($property)){
        $the_object->$property = $value;
      }
    }
    return $the_object;
  }//instantiate

  private function has_the_property($the_property) {
    $object_properties = get_object_vars($this);
    return array_key_exists($the_property, $object_properties);
  }//has_the_property

}




 ?>