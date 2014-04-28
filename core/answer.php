<?php
    require_once("config.php");

    class Answer {

        protected static $table_name="tbl_answers";
        public $answer_id;
        public $user_id;
        public $question_id;
        public $answer_body ;
        public $published_date;
        public $sent_date;

        public static function get_table_name(){
            return self::$table_name;
        }
        
        public static function find_unsent(){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE sent_date is NULL";
            $sql .= " ORDER BY published_date DESC";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_sent($last = 10){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " ORDER BY sent_date DESC";
            $sql .= " LIMIT " . $last;

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_top_answered_users($limit=5){
            $sql  = " SELECT user_id, COUNT( * ) ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " GROUP BY user_id";
            $sql .= " ORDER BY COUNT( * ) DESC";
            $sql .= " LIMIT " . $limit;
            
            
            global $database;
            $result_set = $database->query($sql);
            while ($row = $database->fetch_array($result_set)) {
                $object_array[] = $row;
            }
            return $object_array;
        }

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

        public static function find_by_id($answer_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE answer_id='{$answer_id}' LIMIT 1");
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
        
        public static function find_by_user($user_id) {
            $sql  = ' SELECT * ';
            $sql .= ' FROM '.self::$table_name;
            $sql .= ' WHERE user_id = "'.$user_id.'"';
            
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function delete_by_id($answer_id=0) {
            global $database;
            $sql  = ' DELETE FROM '.self::$table_name;
            $sql .= ' WHERE answer_id ="'.$answer_id.'"';
            $sql .= ' LIMIT 1';
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public static function delete_by_question_id($question_id=0) {
            global $database;
            $sql  = ' DELETE FROM '.self::$table_name;
            $sql .= ' WHERE question_id ="'.$question_id.'"';
            
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
    
        public static function find_answered_questions($limit = 3){
            $sql = ' SELECT DISTINCT question_id ';
            $sql.= ' FROM ' . self::$table_name;
            $sql.= ' ORDER BY RAND() ';
            $sql.= ' LIMIT ' . $limit;
            
            global $database;
            $result_set = $database->query($sql);
            while ($row = $database->fetch_array($result_set)) {
                $object_array[] = $row;
            }
            return $object_array;
        }
        
        public static function count_answers($question_id){
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
            $sql .="user_id, question_id, answer_body, sent_date, published_date";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->user_id) . "', '";
            $sql .= $database->escape_value($this->question_id) . "', '";
            $sql .= $database->escape_value($this->answer_body) . "', '";
            $sql .= $database->escape_value($this->sent_date) . "', '";
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
            $sql .= ", answer_body = '" . $database->escape_value($this->answer_body)."' ";
            $sql .= ", sent_date = '" . $database->escape_value($this->sent_date)."' ";
            $sql .= ", published_date = '" . $database->escape_value($this->published_date)."' ";
            $sql .= " WHERE answer_id = " . $this->answer_id;

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