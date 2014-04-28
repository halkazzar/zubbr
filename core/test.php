<?php
    require_once("config.php");

    class Test {

        protected static $table_name="tbl_tests";
        
        public $test_id;
        public $test_title;
        public $test_desc;
        public $test_status;
        public $test_access;
        public $test_time;
        public $time_type;
        public $lang_id;
        public $cat_id;
        public $usr_id;
        
        
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
        
        public static function count_by_lang($lang_id="0"){
            global $database;
            $sql = "SELECT count(*) FROM ".self::$table_name." WHERE lang_id={$lang_id}";
            $result = $database->query($sql);
            return !empty($result) ? array_shift($database->fetch_array($result)): false;
        }
        
       public static function count_all() {
            $sql = " SELECT COUNT(*) ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" ORDER BY test_title DESC";
            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
        }
        
        
        public static function find_by_id($test_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE test_id={$test_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_random($num=3) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE test_status='online' ORDER BY RAND() LIMIT {$num}");
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_by_lang($lang_id, $limit='0', $offset='-1') {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE lang_id='{$lang_id}'";
            $sql .=" AND test_status='online'";
            $sql .=" ORDER BY test_title DESC";
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function delete_by_id($test_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE test_id={$test_id} LIMIT 1";
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
            $sql .="test_title, test_desc, test_status, test_access, test_time, time_type, lang_id, cat_id, usr_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->test_title) . "', '";
            $sql .= $database->escape_value($this->test_desc) . "', '";
            $sql .= $database->escape_value($this->test_status) . "', '";
            $sql .= $database->escape_value($this->test_access) . "', '";
            $sql .= $database->escape_value($this->test_time) . "', '";
            $sql .= $database->escape_value($this->test_time_type) . "', '";
            $sql .= $database->escape_value($this->lang_id) . "', '";
            $sql .= $database->escape_value($this->cat_id) . "', '";
            $sql .= $database->escape_value($this->usr_id) . "')";
            if($database->query($sql)){
                $this->test_id = $database->insert_id();
                return $database->insert_id();
            } else {
                return false;
            }
        }

        public function update() {
            global $database;
            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " test_title = '" . $database->escape_value($this->test_title) ."' ";
            $sql .= ", test_desc = '" . $database->escape_value($this->test_desc) ."' ";
            $sql .= ", test_status = '" . $database->escape_value($this->test_status) ."' ";
            $sql .= ", test_access = '" . $database->escape_value($this->test_access) ."' ";
            $sql .= ", test_time = '" . $database->escape_value($this->test_time) ."' ";
            $sql .= ", time_type = '" . $database->escape_value($this->test_time_type) ."' ";
            $sql .= ", lang_id = '" . $database->escape_value($this->lang_id) ."' ";
            $sql .= ", cat_id = '" . $database->escape_value($this->cat_id) ."' ";
            $sql .= ", usr_id = '" . $database->escape_value($this->usr_id)."' ";
            $sql .= " WHERE test_id = " . $this->test_id;
            //echo $sql;
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public function save(){
            if (isset($this->test_id)){
                if (self::find_by_id($this->test_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>