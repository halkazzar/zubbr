<?php
    require_once("config.php");

    class Tag {

        protected static $table_name="tbl_tags";
        public $tag_id;
        public $tag_name;
        public $group_id;
        public $is_default;

        public static function get_tablename(){
            return self::$table_name;
        }
        
        public static function find_by_name($tag_name=""){
            global $database;
            $sql = " SELECT * FROM " . self::$table_name
                 . " WHERE tag_name LIKE '%{$tag_name}%'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_all(){
            global $database;
            $sql = " SELECT * FROM " . self::$table_name
                 . " ORDER BY group_id, tag_id ASC";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_exact_name($tag_name=""){
            global $database;
            $sql = " SELECT * FROM " . self::$table_name
                 . " WHERE tag_name = '{$tag_name}'"
                 . " LIMIT 1";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_by_id($tag_id=0){
            global $database;
            $sql = " SELECT * FROM " . self::$table_name
                 . " WHERE tag_id = '{$tag_id}'"
                 . " LIMIT 1";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_groups(){
            global $database;
            $sql = " SELECT DISTINCT group_id FROM " . self::$table_name;
            $sql .= " ORDER BY group_id ASC";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function get_last_group(){
            global $database;
            $sql = " SELECT MAX(group_id) FROM " . self::$table_name;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];    
        }
        
        public static function find_by_group_id($group_id=0){
            global $database;
            $sql = " SELECT * FROM " . self::$table_name
                 . " WHERE group_id = '{$group_id}'";
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
        
        public static function delete_by_id($tag_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE tag_id={$tag_id} LIMIT 1";
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
            $sql .="tag_name, group_id, is_default";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->tag_name) . "', '";
            $sql .= $database->escape_value($this->group_id) . "', '";
            $sql .= $database->escape_value($this->is_default) . "')";
            if($database->query($sql)){
                $this->tag_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {
            global $database;
            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " tag_name = '" . $database->escape_value($this->tag_name) ."' ";
            $sql .= ", group_id = " . $database->escape_value($this->group_id)." ";
            $sql .= ", is_default = " . $database->escape_value($this->is_default)." ";
            $sql .= " WHERE tag_id = " . $this->tag_id;
            echo $sql;
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        
        public function save(){
            if (isset($this->tag_id)){
                if (self::find_by_id($this->tag_id)) $this->update();
            }
            else $this->create();
            return true;   
        }
    }

?>