<?php
require_once("config.php");

class Testquestiontype {
    
    protected static $table_name="tbl_test_question_types";
    public $question_type_id;
    public $question_type_name;
    public $question_type_desc;


        public static function delete_by_id($question_type_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE question_type_id={$question_type_id} LIMIT 1";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
    
        public static function find_all() {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
    
  public static function find_by_sql($sql="") {
    global $database;
    $result_set = $database->query($sql);
    $object_array = array();
    while ($row = $database->fetch_array($result_set)) {
      $object_array[] = self::instantiate($row);
    }
    return $object_array;
  }
    
     public static function find_by_id($question_type_id=0) {
    $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE question_type_id={$question_type_id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
  }
    
    
    private static function instantiate($record) {
    $object = new self;
        foreach($record as $attribute=>$value){
          if($object->has_attribute($attribute)) {
            $object->$attribute = $value;
          }
        }
        return $object;
    }
    
    private function has_attribute($attribute) {
      $object_vars = get_object_vars($this);
      return array_key_exists($attribute, $object_vars);
    }    
    
    public function create() {
    global $database;
    $sql = "INSERT INTO ".self::$table_name." (";
    $sql .="question_type_name, question_type_desc";
    $sql .=") VALUES ('";
    $sql .= $database->escape_value($this->question_type_name) . "', '";
    $sql .= $database->escape_value($this->question_type_desc) . "')";
    if($database->query($sql)){
        $this->question_id = $database->insert_id();
        return true;
    } else {
        return false;
    }
    }
    
    public function update() {
    global $database;
    $sql = "UPDATE ".self::$table_name;
    $sql .= " SET ";
    $sql .= " question_type_name = '" . $database->escape_value($this->question_type_name) ."' ";
    $sql .= ", question_type_desc = '" . $database->escape_value($this->question_type_desc)."' ";
    $sql .= " WHERE question_type_id = " . $this->question_type_id;
    
    if($database->query($sql)){
        return true;
    } else {
        return false;
    }
    }
    
    public function save(){
            if (isset($this->question_type_id)){
                if (self::find_by_id($this->question_type_id)) $this->update();
            }
            else $this->create();
            return true;   
        }
}

?>