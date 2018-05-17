<?php 


class User extends Db_object {

  protected static $db_table = "users";
  protected static $db_table_fields = array(
    'username',
    'password',
    'first_name',
    'last_name',
    'filename',
    'type',
    'size'
  );

  public $username;
  public $first_name;
  public $last_name;
  public $password;
  public $image_placeholder = "http://placehold.it/400x400&text=image";


  public function image_placeholder() {
    return empty($this->filename) ? $this->image_placeholder : $this->picture_path();
  }

  public function save_user_data() {
      $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;
      if(!empty($this->errors)){
        return false;
      }
      if(file_exists($target_path)) {
        $this->errors[] = "The file, {$this->filename}, already exists";
        return false;
      }
      if(move_uploaded_file($this->tmp_path, $target_path)) {
          unset($this->tmp_path);
        } else {
          $this->errors[] = "You do not have permission to save to this directory";
          return false;
        }
    }// save method

  public static function verify_user($username, $password) {
    global $database;
    $username = $database->escape_string($username);
    $password = $database->escape_string($password);
    $sql = "SELECT * FROM " .self::$db_table . " WHERE ";
    $sql .= "username = '{$username}' ";
    $sql .= "AND password = '{$password}' ";
    $sql .= "LIMIT 1";
    $result_array = self::find_by_query($sql);
    return !empty($result_array) ? array_shift($result_array) : false;
  } //verify users
  
  public function delete_user() {
    if($this->delete()) {
      $target_path = SITE_ROOT.DS.'admin'.DS.$this->picture_path();
      return unlink($target_path) ? true : false;
    } else {
      return false;
    }

  }
}//end of user class

?>