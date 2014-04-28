<?php
    require_once("config.php");

    class Vote {

        protected static $table_name="tbl_votes";
        public $vote_id;
        public $user_id;
        public $answer_id;

        public static function find_by_user($user_id) {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE user_id='{$user_id}'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }        
        
        public static function find_by_answer($answer_id) {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE answer_id='{$answer_id}'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_all($answer_id, $user_id) {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE answer_id='{$answer_id}'";
            $sql .=" AND user_id='{$user_id}'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function delete_by_id($vote_id=0) {
            global $database;
            $sql  = ' DELETE FROM '.self::$table_name;
            $sql .= ' WHERE vote_id = "'.$vote_id.'"';
            $sql .= ' LIMIT 1';
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public static function find_all($limit='0', $offset='-1') {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
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

        public static function find_by_id($vote_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE vote_id='{$vote_id}' LIMIT 1");
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
            $sql .="user_id, answer_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->user_id) . "', '";
            $sql .= $database->escape_value($this->answer_id) . "')";
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
            $sql .= ", answer_id = '" . $database->escape_value($this->answer_id)."' ";
            $sql .= " WHERE vote_id = " . $this->vote_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->vote_id)){
                if (self::find_by_id($this->vote_id)) $this->update();
            }
            else $this->create();
            return true;   
        }
    }

?>