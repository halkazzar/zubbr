<?php
    require_once("config.php");

    class Jobposting {

        protected static $table_name="tbl_jobpostings";
        public $jobposting_id;
        public $body;
        public $date_of_publish;
        public $title;
        public $author_id;

        public static function find_random($num=4) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY RAND() LIMIT {$num}");
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_recent($limit='0', $offset='-1') {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" ORDER BY date_of_publish DESC";
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            if (($limit > 0) && ($offset == -1)){
                $sql .= " LIMIT " . $limit;
            }
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        


        public static function find_all($per_page = 0, $limit = 0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY  date_of_publish DESC");
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

        public static function find_by_id($jobposting_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE jobposting_id={$jobposting_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function delete_by_id($jobposting_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE jobposting_id={$jobposting_id} LIMIT 1";
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
            $sql .="title, body, date_of_publish, author_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->title) . "', '";
            $sql .= $database->escape_value($this->body) . "', '";
            $sql .= $database->escape_value($this->date_of_publish) . "', '";
            $sql .= $database->escape_value($this->author_id) . "')";
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
            $sql .= ", body = '" . $database->escape_value($this->body)."' ";
            $sql .= ", date_of_publish = '" . $database->escape_value($this->date_of_publish)."' ";
            $sql .= ", author_id = '" . $database->escape_value($this->author_id)."' ";
            $sql .= " WHERE jobposting_id = " . $this->jobposting_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->jobposting_id)){
                if (self::find_by_id($this->jobposting_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>