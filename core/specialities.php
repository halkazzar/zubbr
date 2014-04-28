<?php
    require_once("config.php");

    class Speciality {

        protected static $table_name="tbl_university_specialities";
        public $university_speciality_id;
        public $university_id;
        public $degree_id;
        public $studyarea_id;

        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name);
            return !empty($result_array) ? $result_array : false;
        }

        public static function count_studyarea($university_id){
            $sql  = " SELECT COUNT(studyarea_id) ";
            $sql .= " FROM " .self::$table_name ;
            $sql .= " WHERE university_id = " . $university_id;

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

        public static function find_by_id($university_speciality_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE university_speciality_id={$university_speciality_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_university_id($university_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE university_id={$university_id}");
            return !empty($result_array) ? $result_array : false;
        }

        public static function delete_by_id($university_speciality_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE university_speciality_id={$university_speciality_id} LIMIT 1";
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
            $sql .="university_id, degree_id, studyarea_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->university_id) . "', '";
            $sql .= $database->escape_value($this->degree_id) . "', '";
            $sql .= $database->escape_value($this->studyarea_id) . "')";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ". self::$table_name;
            $sql .= " SET ";
            $sql .= " university_id = " . $database->escape_value($this->university_id) .", ";
            $sql .= " degree_id = " . $database->escape_value($this->degree_id) .", ";
            $sql .= " studyarea_id = " . $database->escape_value($this->studyarea_id);
            $sql .= " WHERE university_speciality_id = " . $this->university_speciality_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->university_speciality_id)){
                if (self::find_by_id($this->university_speciality_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>