<?php
    require_once("config.php");

    class Promocode {

        protected static $table_name="tbl_promocodes";
        public $promocode_id;
        public $title;
        public $registered;

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

        public static function find_by_id($promocode_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE promocode_id={$promocode_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_title($title='') {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE title='{$title}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function delete_by_id($promocode_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE promocode_id={$promocode_id} LIMIT 1";
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
            $sql .="title, registered";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->title) . "', '";
            $sql .= $database->escape_value($this->registered) . "')";
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
            $sql .= " title = '" . $database->escape_value($this->title) ."' ";
            $sql .= ", registered = '" . $database->escape_value($this->registered)."' ";
            $sql .= " WHERE promocode_id = " . $this->promocode_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->promocode_id)){
                if (self::find_by_id($this->promocode_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>
