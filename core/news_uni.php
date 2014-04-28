<?php
    require_once("config.php");

    class News_uni {

        protected static $table_name="tbl_news_uni";
        public $news_uni_id;
        public $news_id;
        public $university_id;

        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name);
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_universities($news_id){
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE news_id = {$news_id}";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_news($uni_id){
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE university_id = {$uni_id}";

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

        public static function find_by_id($news_uni_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE news_uni_id={$news_uni_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function delete_by_id($news_uni_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE news_uni_id={$news_uni_id} LIMIT 1";
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
            $sql .="news_id, university_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->news_id) . "', '";
            $sql .= $database->escape_value($this->university_id) . "')";
            
            if($database->query($sql)){
                $this->news_uni_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " news_id = '" . $database->escape_value($this->news_id) ."' ";
            $sql .= ", university_id = '" . $database->escape_value($this->university_id)."' ";
            $sql .= " WHERE news_uni_id = " . $this->news_uni_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->news_uni_id)){
                if (self::find_by_id($this->news_uni_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>
