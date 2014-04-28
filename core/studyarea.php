<?php
    require_once("config.php");

    class Studyarea {

        protected static $table_name="tbl_studyareas";
        public $studyarea_id;
        public $title;
        public $parent_id;

        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY title");
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

        public static function find_by_id($studyarea_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE studyarea_id={$studyarea_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_title($title) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE title='{$title}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_parents(){
            $sql  = ' SELECT * ';
            $sql .= ' FROM ' . self::$table_name;
            $sql .= ' WHERE parent_id = 0';
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_children(){
            $sql  = ' SELECT * ';
            $sql .= ' FROM ' . self::$table_name;
            $sql .= ' WHERE studyarea_id <> 0';
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_parent_id($id=0){
            $sql  = ' SELECT * ';
            $sql .= ' FROM ' . self::$table_name;
            $sql .= " WHERE parent_id = '{$id}'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function delete_by_id($studyarea_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE studyarea_id={$studyarea_id} LIMIT 1";
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
            $sql .="title, parent_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->title) . "', '";
            $sql .= $database->escape_value($this->parent_id) . "')";
            if($database->query($sql)){
                $this->studyarea_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " title = '" . $database->escape_value($this->title) ."', ";
            $sql .= " parent_id = '" . $database->escape_value($this->parent_id) ."' ";
            $sql .= " WHERE studyarea_id = " . $this->studyarea_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->studyarea_id)){
                if (self::find_by_id($this->studyarea_id)) $this->update();
            }
            else {
                $this->create();    
            }
            return true;   
        }

    }

?>