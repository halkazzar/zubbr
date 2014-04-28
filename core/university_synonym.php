<?php
    require_once("config.php");

    class Synonym {

        protected static $table_name="tbl_university_synonyms";
        public $university_synonym_id;
        public $university_id;
        public $title;

        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY title");
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_all_published() {
            $sub_sql  = " SELECT university_id FROM " . University::get_tablename();
            $sub_sql .= " WHERE status = 'draft'";
            
            $sql  = " SELECT * FROM ".self::$table_name;
            $sql .= " WHERE university_id NOT IN (".$sub_sql.")";
            
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

        public static function find_by_title($title) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE title='{$title}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_by_id($university_synonym_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE university_synonym_id={$university_synonym_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_by_university($university_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE university_id='{$university_id}'");
            return !empty($result_array) ? $result_array : false;
        }

        public static function delete_by_id($university_synonym_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE university_synonym_id={$university_synonym_id} LIMIT 1";
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
            $sql .="university_id, title";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->university_id) . "', '";
            $sql .= $database->escape_value($this->title) . "')";
            if($database->query($sql)){
                $this->university_synonym_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " university_id = '" . $database->escape_value($this->university_id) ."', ";
            $sql .= " title = '" . $database->escape_value($this->title) ."' ";
            $sql .= " WHERE university_synonym_id = " . $this->university_synonym_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->university_synonym_id)){
                if (self::find_by_id($this->university_synonym_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>