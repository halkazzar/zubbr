<?php
    require_once("config.php");

    class Passed_test {

        protected static $table_name="tbl_passed_tests";
        
        public $passed_test_record_id;
        public $user_id;
        public $test_id;
        public $date;
        public $status;
        
        //public static function get_already_answered(){
//            global $database;
//            $sql = "SELECT answer_id  FROM ".self::$table_name." WHERE passed_test_record_id=".$_SESSION['passed_test_record_id']." AND question_id = '{$question_id}'";
//            $result = $database->query($sql);
//            return !empty($result) ? array_shift($database->fetch_array($result)): false;
//            
//        }
        
 
        
        public static function get_passed_test_record(){
            global $database;
            $sql = "SELECT passed_test_record_id  FROM ".self::$table_name." ORDER BY passed_test_record_id DESC LIMIT 0,1";
            $result = $database->query($sql);
            return !empty($result) ? array_shift($database->fetch_array($result)): false;
            
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

        
        public static function test_passed_count($test_id="0"){
            global $database;
            $sql = "SELECT count(*) AS 'Rows' FROM ".self::$table_name." WHERE test_id='{$test_id}' GROUP BY  test_id";
            $result = $database->query($sql);
            return !empty($result) ? array_shift($database->fetch_array($result)): false;
        }
        
        
        
        public static function find_by_id($passed_test_record_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE passed_test_record_id={$passed_test_record_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_by_user($user_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE user_id={$user_id} ORDER BY date DESC");
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function delete_by_id($passed_test_record_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE passed_test_record_id={$passed_test_record_id} LIMIT 1";
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
            $sql .="user_id, test_id, date, status";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->user_id) . "', '";
            $sql .= $database->escape_value($this->test_id) . "', '";
            $sql .= $database->escape_value($this->date) . "', '";
            $sql .= $database->escape_value($this->status) . "')";
            if($database->query($sql)){
                $this->passed_test_record_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {
            global $database;
            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " user_id = '" . $database->escape_value($this->user_id) ."' ";
            $sql .= ", test_id = '" . $database->escape_value($this->test_id) ."' ";
            $sql .= ", date = '" . $database->escape_value($this->date) ."' ";
            $sql .= ", status = '" . $database->escape_value($this->status) ."' ";
            $sql .= " WHERE passed_test_record_id = " . $this->passed_test_record_id;
            //echo $sql;
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public function save(){
            if (isset($this->passed_test_record_id)){
                if (self::find_by_id($this->passed_test_record_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>