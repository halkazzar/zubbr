<?php
    require_once("config.php");

    class Passed_test_data {

        protected static $table_name="tbl_passed_tests_data";
        
        public $id;
        public $passed_test_record_id;
        public $question_id;
        public $answer_id;
        
        public static function find_by_record_question($passed_test_record, $question_id){
            $sql = "SELECT * FROM ".self::$table_name." WHERE passed_test_record_id='".$passed_test_record."' AND question_id = '{$question_id}' LIMIT 1";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_by_record($passed_test_record){
            $sql = "SELECT * FROM ".self::$table_name." WHERE passed_test_record_id='".$passed_test_record."'";
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

        
        public static function find_by_id($id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        
        
        public static function delete_by_id($id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE id={$id} LIMIT 1";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
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
            $sql .="passed_test_record_id, question_id, answer_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->passed_test_record_id) . "', '";
            $sql .= $database->escape_value($this->question_id) . "', '";
            $sql .= $database->escape_value($this->answer_id) . "')";
            if($database->query($sql)){
                $this->id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {
            global $database;
            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " passed_test_record_id = '" . $database->escape_value($this->passed_test_record_id) ."' ";
            $sql .= ", question_id = '" . $database->escape_value($this->question_id) ."' ";
            $sql .= ", answer_id = '" . $database->escape_value($this->answer_id) ."' ";
            $sql .= " WHERE id = " . $this->id;
            
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public function save(){
            if (isset($this->id)){
                if (self::find_by_id($this->id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>