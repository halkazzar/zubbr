<?php
    require_once("config.php");

    class Tagmap {

        protected static $table_name="tbl_tagmap";
        public $tagmap_id;
        public $object_type;
        public $object_id;
        public $tag_id;

        public static function get_table_name(){
            return self::$table_name;
        }
        
        public static function test_get_tags($test_id="0"){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name
                 . " WHERE object_id = {$test_id} "
                 . " AND object_type = 'test'";
            
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        
        public static function question_get_tags($question_id="0"){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name
                 . " WHERE object_id = {$question_id} "
                 . " AND object_type = 'question'";
            
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        
        
        public static function news_get_tags($news_id="0"){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name
                 . " WHERE object_id = {$news_id} "
                 . " AND object_type = 'news'";
            
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY title");
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_tag_id($tag_id="0"){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name
                 . " WHERE tag_id = '{$tag_id}' ";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_object_type_object_id_tag_id($object_type="-1", $object_id='-1', $tag_id='-1'){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name
                 . " WHERE object_type = '{$object_type}' "
                 . " AND object_id = '{$object_id}' "
                 . " AND tag_id = '{$tag_id}' ";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_object_type($object_type="question"){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name
                 . " WHERE object_type = '{$object_type}' ";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_by_object_type_tag_id($object_type="-1", $tag_id='-1'){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name
                 . " WHERE object_type = '{$object_type}' "
                 . " AND tag_id = '{$tag_id}' "
                 . " ORDER BY tagmap_id DESC";
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

        public static function find_by_id($tagmap_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE tagmap_id={$tagmap_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function delete_by_id($tagmap_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE tagmap_id={$tagmap_id} LIMIT 1";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public static function delete_by_question_id($question_id=0) {
            global $database;
            $sql  = ' DELETE FROM '.self::$table_name;
            $sql .= ' WHERE object_type = "question" AND object_id = "'.$question_id.'"';
            
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
            $sql .="object_type, object_id, tag_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->object_type) . "', '";
            $sql .= $database->escape_value($this->object_id) . "', '";
            $sql .= $database->escape_value($this->tag_id) . "')";
            if($database->query($sql)){
                $this->tagmap_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " object_type = '" . $database->escape_value($this->object_type) ."', ";
            $sql .= " object_id = '" . $database->escape_value($this->object_id) ."', ";
            $sql .= " tag_id = '" . $database->escape_value($this->tag_id) ."' ";
            $sql .= " WHERE tagmap_id = " . $this->tagmap_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->tagmap_id)){
                if (self::find_by_id($this->tagmap_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>
