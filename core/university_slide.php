<?php
    require_once("config.php");

    class UniversitySlide {

        protected static $table_name="tbl_university_slides";
        public $slides_id;
        public $university_id;
        public $picture_extension;
        public $label;
        public $link;

        public static function delete_by_id($slides_id) {
            $sql = "DELETE FROM ".self::$table_name." WHERE slides_id = " . $slides_id . " LIMIT 1";
            
            global $database;
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
     
        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY university_id");
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

        public static function find_by_id($slides_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE slides_id={$slides_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_university_id($university_id=0) {
            
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE university_id={$university_id}");
            return !empty($result_array) ? $result_array : false; 
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

        
        //Redo these
        public function create() {
            global $database;
            $sql = "INSERT INTO ".self::$table_name." (";
            $sql .="slides_id, university_id,  picture_extension, label, link";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->slides_id) . "', '";
            $sql .= $database->escape_value($this->university_id) . "', '";
            $sql .= $database->escape_value($this->picture_extension) . "', '";
            $sql .= $database->escape_value($this->label) . "', '";
            $sql .= $database->escape_value($this->link) . "')";
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
            $sql .= " slides_id = '" . $database->escape_value($this->slides_id) ."' ";
            $sql .= ", university_id = '" . $database->escape_value($this->university_id)."' ";
            $sql .= ", picture_extension = '" . $database->escape_value($this->picture_extension)."' ";
            $sql .= ", label = '" . $database->escape_value($this->label)."' ";
            $sql .= ", link = '" . $database->escape_value($this->link)."' ";
            $sql .= " WHERE slides_id = " . $this->slides_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public function save(){
            if (isset($this->slides_id)){
                if (self::find_by_id($this->slides_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>