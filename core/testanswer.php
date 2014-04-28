<?php
    require_once("config.php");

    class Testanswer {

        protected static $table_name="tbl_test_answers";
        public $answer_id;
        public $answer_body;
        public $answer_type_id;
        public $answer_right;
        public $question_id;

        public static function delete_by_id($answer_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE answer_id={$answer_id} LIMIT 1";
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
        
        public static function find_by_question_id($question_id) {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE question_id = '" . $question_id . "'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }

        public static function count_by_question_id($question_id) {
            $sql = " SELECT COUNT(*) ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE question_id = '" . $question_id . "'";
            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
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

        public static function find_by_id($answer_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE answer_id={$answer_id} LIMIT 1");
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
            $sql .="answer_body, answer_type_id, answer_right, question_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->answer_body) . "', '";
            $sql .= $database->escape_value($this->answer_type_id) . "', '";
            $sql .= $database->escape_value($this->answer_right) . "', '";
            $sql .= $database->escape_value($this->question_id) . "')";
            if($database->query($sql)){
                $this->answer_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {
            global $database;
            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " answer_body = '" . $database->escape_value($this->answer_body) ."' ";
            $sql .= ", answer_type_id = '" . $database->escape_value($this->answer_type_id)."' ";
            $sql .= ", answer_right = '" . $database->escape_value($this->answer_right)."' ";
            $sql .= ", question_id = '" . $database->escape_value($this->question_id)."' ";
            $sql .= " WHERE answer_id = " . $this->answer_id;
            echo $sql;
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public function save(){
            if (isset($this->answer_id)){
                if (self::find_by_id($this->answer_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>