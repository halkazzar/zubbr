<?php
    require_once("config.php");

    class Complaint {

        protected static $table_name="tbl_complaints";
        public $complaint_id;
        public $user_id;
        public $question_id;
        public $complaint_body ;
        public $published_date;


        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name);
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

        public static function find_by_id($complaint_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE complaint_id={$question_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_question_id($question_id=0) {
            $sql  = ' SELECT * ';
            $sql .= ' FROM '.self::$table_name;
            $sql .= ' WHERE question_id = "'.$question_id.'"';
            $sql .= ' ORDER BY published_date';
            
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function count_complaints($question_id){
            $sql  = " SELECT COUNT(*) ";
            $sql .= " FROM " .self::$table_name ;
            $sql .= " WHERE question_id = " . $question_id;

            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
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
            $sql .="user_id, question_id, complaint_body, published_date";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->user_id) . "', '";
            $sql .= $database->escape_value($this->question_id) . "', '";
            $sql .= $database->escape_value($this->complaint_body) . "', '";
            $sql .= $database->escape_value($this->published_date) . "')";
            if($database->query($sql)){
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
            $sql .= ", question_id = '" . $database->escape_value($this->question_id)."' ";
            $sql .= ", complaint_body = '" . $database->escape_value($this->complaint_body)."' ";
            $sql .= ", published_date = '" . $database->escape_value($this->published_date)."' ";
            $sql .= " WHERE complaint_id = " . $this->complaint_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->complaint_id)){
                if (self::find_by_id($this->complaint_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>