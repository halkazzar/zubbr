<?php
require_once("config.php");

class Testquestion {
	
	protected static $table_name="tbl_test_questions";
	public $question_id;
    public $question_body;
    public $question_type_id;
    public $test_id;

    public static function get_new_question($test_id, $and, $sort, $limit){
    $sql = "SELECT * FROM " . self::$table_name . " WHERE test_id = '{$test_id}' $and ORDER BY question_id $sort LIMIT $limit ";
    $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public static function get_previous_question($test_id, $question_id){
    $sql = "SELECT * FROM " . self::$table_name . " WHERE test_id = '{$test_id}' AND question_id < '{$question_id}' ORDER BY question_id ASC LIMIT 0,1 ";
    $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public static function get_next_question($test_id, $question_id){
    $sql = "SELECT * FROM " . self::$table_name . " WHERE test_id = '{$test_id}' AND question_id > '{$question_id}' ORDER BY question_id ASC LIMIT 0,1";
    $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

	public static function list_all_questions($test_id){
	$sql = "SELECT * FROM " . self::$table_name . " WHERE test_id = {$test_id} ORDER BY question_id ";
	$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
	}
	
        public static function delete_by_id($question_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE question_id={$question_id} LIMIT 1";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
    
        public static function find_all() {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" ORDER BY test_id";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
	
        public static function find_by_test_id($test_id) {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE test_id = {$test_id}";
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
	
	 public static function find_by_id($question_id=0) {
    $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE question_id={$question_id} LIMIT 1");
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
	$sql .="question_body, question_type_id, test_id";
	$sql .=") VALUES ('";
    $sql .= $database->escape_value($this->question_body) . "', '";
	$sql .= $database->escape_value($this->question_type_id) . "', '";
	$sql .= $database->escape_value($this->test_id) . "')";
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
	$sql .= " question_body = '" . $database->escape_value($this->question_body) ."' ";
    $sql .= ", question_type_id = '" . $database->escape_value($this->question_type_id)."' ";
	$sql .= ", test_id = '" . $database->escape_value($this->test_id)."' ";
	$sql .= " WHERE question_id = " . $this->question_id;
	echo $sql;
	if($database->query($sql)){
		return true;
	} else {
		return false;
	}
	}
	
    public function save(){
            if (isset($this->question_id)){
                if (self::find_by_id($this->question_id)) $this->update();
            }
            else $this->create();
            return true;   
        }
}

?>