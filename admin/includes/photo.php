<?php 


class Photo extends Db_object {

  protected static $db_table = "photos";
  protected static $db_table_fields = array(
    'title',
    'caption',
    'description',
    'filename',
    'alternate_text',
    'type',
    'size',
  );
  
  public $title;
  public $description;
  public $alternate_text;
  public $caption;

 

  //This is passing $_FILES['uploaded_file'] as an argument
  
  
 


  public function save_data() {
    if($this->id) {
      $this->update();
    } else {
      $target_path = SITE_ROOT . DS . 'admin' . DS . $this->picture_path();
      if(!empty($this->errors)){
        return false;
      }
      if(empty($this->filename) || empty($this->tmp_path)){
        $this->errors[] = "the file was not available";
        return false;
      }
      if(file_exists($target_path)) {
        $this->errors[] = "The file, {$this->filename}, already exists";
        return false;
      }
      if(move_uploaded_file($this->tmp_path, $target_path)) {
        if($this->create()) {
          unset($this->tmp_path);
          return true;
        } else {
          $this->errors[] = "You do not have permission to save to this directory";
          return false;
        }
      }
    }
  }// save method

  public function delete_photo() {
    if($this->delete()) {
      $target_path = SITE_ROOT.DS.'admin'.DS.$this->picture_path();
      return unlink($target_path) ? true : false;
    } else {
      return false;
    }

  }
}

?>