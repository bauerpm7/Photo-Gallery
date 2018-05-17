<?php 

class Db_object{

  public $id;
  public $type;
  public $size;
  public $filename;
  public $tmp_path;
  public $upload_directory = "images";
  public $errors = array();
  public $upload_errors_array = array(
    UPLOAD_ERR_OK           => "There is no error",
    UPLOAD_ERR_INI_SIZE     => "The file exceeds the max upload file size.",
    UPLOAD_ERR_FORM_SIZE    => "The file exceeds the max file size",
    UPLOAD_ERR_PARTIAL      => "The file was only partially uploaded",
    UPLOAD_ERR_NO_FILE      => "No file was uploaded",
    UPLOAD_ERR_NO_TMP_DIR   => "Missing a temporary folder",
    UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk",
    UPLOAD_ERR_EXTENSION    => "A PHP extension stopped the file upload",
  );

  public static function find_all() {
    return static::find_by_query("SELECT * FROM " . static::$db_table);
  }//find_all

  public static function find_by_id($id) {
    global $database;
    $result_array = static::find_by_query("SELECT * FROM " . static::$db_table ." WHERE id = $id LIMIT 1" );
    return !empty($result_array) ?  array_shift($result_array) : false;
  }//find_by_id

  public static function find_by_query($sql){
    global $database; 
    $result_set = $database->query($sql);
    $the_object_array = array();
    while($row = mysqli_fetch_array($result_set)){
      $the_object_array[] = static::instantiate($row);
    }
    return $the_object_array;
  }//find_by_query

  public static function instantiate($the_record) {
    $calling_class = get_called_class();
    $the_object = new $calling_class();
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

  protected function properties_to_array() {
    $properties = array();

    foreach (static::$db_table_fields as $db_field) {
      if(property_exists($this, $db_field)){
        $properties[$db_field] = $this->$db_field;
      }
    }
    return $properties;
  }//properties

  protected function escape_properties () {
    global $database;
    $escaped_properties = array();
    foreach ($this->properties_to_array() as $key => $value) {
      $escaped_properties[$key] = $database->escape_string($value);
    }
    return $escaped_properties;
  }

  public function set_file($file) {

    if(empty($file) || !$file || !is_array($file)) {
      $this->errors[] = "There was no file uploaded here";
      return false;
    } elseif($file['error'] !=0){
      $this->errors[] = $this->upload_errors_array[$file['error']]; 
      return false;  
    } else {
      $this->filename = basename($file['name']);
      $this->tmp_path = $file['tmp_name'];
      $this->type     = $file['type'];
      $this->size     = $file['size'];
    }
  }
   public function picture_path (){
    return $this->upload_directory.DS.$this->filename;
  }
   
    public function save() {
    return isset($this->id) ? $this->update() : $this->create();
  }//save

  public function create() {
    global $database;
    $properties = $this->escape_properties();
    $sql = "INSERT INTO " .static::$db_table . "(" . implode(",", array_keys($properties)) . ")";
    $sql .= "Values ('" . implode("','", array_values($properties)) . "')";
    if($database->query($sql)){
      $this->id = $database->insert_id();
      return true;
    } else { return false; }
  } //create method

  public function update(){
    global $database;
    $properties = $this->escape_properties();
    $property_pairs = array();
    foreach ($properties as $key => $value){
      $property_pairs[] = "{$key} = '{$value}'";
    }
    $sql = "UPDATE " .static::$db_table . " SET ";
    $sql .= implode(", ", $property_pairs);
    $sql .= " WHERE id= "   . $database->escape_string($this->id);
    $database->query($sql);
    return (mysqli_affected_rows($database->connection) == 1) ? true : false;
  } //update method

  public function delete () {
    global $database;

    $sql = "DELETE FROM " .static::$db_table . " WHERE id= " . $database->escape_string($this->id);
    $sql .= " LIMIT 1";
    $database->query($sql);
    return (mysqli_affected_rows($database->connection) == 1) ? true : false;
  }

}

?>