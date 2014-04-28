<?php
    require_once("config.php");

    class Optionmap {

        protected static $table_name="tbl_optionsmap";
        public $optionmap_id;
        public $user_id;
        public $option_id;
        public $value;

        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY user_id");
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

        public static function find_by_id($optionmap_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE optionmap_id={$optionmap_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_user_id($user_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE user_id={$user_id}");
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_option_id($option_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE option_id={$option_id}");
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_user_id_option_id($option_id=0, $user_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE option_id='{$option_id}' AND user_id='{$user_id}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function delete_by_id($optionmap_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE optionmap_id={$optionmap_id} LIMIT 1";
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
            $sql .="user_id, option_id, value";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->user_id) . "', ";
            $sql .= "'". $database->escape_value($this->option_id) . "', ";
            $sql .= "'". $database->escape_value($this->value) . "')";
            if($database->query($sql)){
                $this->optionmap_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " user_id = '" . $database->escape_value($this->user_id) ."', ";
            $sql .= " option_id = '" . $database->escape_value($this->option_id) ."', ";
            $sql .= " value = '" . $database->escape_value($this->value) ."' ";
            $sql .= " WHERE optionmap_id = " . $this->optionmap_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->optionmap_id)){
                if (self::find_by_id($this->optionmap_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>