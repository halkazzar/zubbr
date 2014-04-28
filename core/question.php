<?php
    require_once("config.php");

    class Question {
        protected static $table_name="tbl_questions";
        public $question_id;
        public $user_id;
        public $question_body ;
        public $question_category;
        public $category_object_id;
        public $published_date;
        public $sent_date;

        public static function find_unsent(){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE sent_date is NULL";
            $sql .= " ORDER BY published_date DESC";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_string($string=''){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE question_body LIKE '%".$string."%'";
            $sql .= " ORDER BY published_date DESC";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_unanswered($limit = 0, $offset = -1){
            $sub_sql  = " SELECT DISTINCT question_id ";
            $sub_sql .= " FROM " . Answer::get_table_name();
            
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE question_id NOT IN (".$sub_sql.")";
            if (($limit == 0) && ($offset == -1)){
                $sql .= " ORDER BY published_date DESC";
            }
            if (($limit > 0) && ($offset > -1)){
                $sql .= " ORDER BY published_date DESC ";
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_top_asked_universities($limit=10){
            $sql  = " SELECT category_object_id, COUNT( * ) ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE question_category = 'university'";
            $sql .= " GROUP BY category_object_id";
            $sql .= " ORDER BY COUNT( * ) DESC";
            $sql .= " LIMIT " . $limit;

            global $database;
            $result_set = $database->query($sql);
            $object_array = array();
            while ($row = $database->fetch_array($result_set)) {
                $object_array[] = $row;
            }
            return $object_array; 
        }

        public static function find_random($num=3) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY RAND() LIMIT {$num}");
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_random_for_stud($num=5, $user_id) {
            $sub_sql  = " SELECT DISTINCT university_id ";
            $sub_sql .= " FROM " . Relation::get_table_name();
            $sub_sql .= " WHERE user_id = '{$user_id}' ";

            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE question_category = 'university'";
            $sql .= " AND category_object_id in (" . $sub_sql . ")";
            $sql .= " ORDER BY RAND()";
            $sql .= " LIMIT {$num}";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_random_for_alumni($num=5, $user_id) {
            $sub_sql  = " SELECT DISTINCT question_id ";
            $sub_sql .= " FROM " . Answer::get_table_name();

            $sub_sql2  = " SELECT DISTINCT university_id ";
            $sub_sql2 .= " FROM " . Relation::get_table_name();
            $sub_sql2 .= " WHERE user_id = '{$user_id}'";
            //$sub_sql2 = " AND role = 'alumni'";

            $sub_sql3  = " SELECT DISTINCT scholarship_id ";
            $sub_sql3 .= " FROM " . Relation::get_table_name();
            $sub_sql3 .= " WHERE user_id = '{$user_id}'";
            //$sub_sql3 = " AND role = 'alumni'";


            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE question_id not in (" . $sub_sql . ")";
            $sql .= " AND ((question_category = 'university' AND category_object_id in (". $sub_sql2 .")) ";
            $sql .= " OR (question_category = 'scholarship' AND category_object_id in (". $sub_sql3 ."))) ";
            $sql .= " ORDER BY RAND()";
            $sql .= " LIMIT {$num}";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }


        public static function delete_by_id($question_id=0) {
            global $database;

            if (Answer::delete_by_question_id($question_id) || (Tagmap::delete_by_question_id($question_id))){

                $sql  = ' DELETE FROM '.self::$table_name;
                $sql .= ' WHERE question_id ="'.$question_id.'"';
                $sql .= ' LIMIT 1';
                if($database->query($sql)){
                    return true;
                } 
            }
            else {
                    return false;
                }
        }

        public static function find_by_object($question_category, $object_id, $limit='0', $offset='-1') {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE question_category='{$question_category}' AND category_object_id = {$object_id}";
            $sql .=" ORDER BY published_date DESC";
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_by_user($user_id) {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE user_id='{$user_id}'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function count_by_object($question_category, $object_id) {
            $sql = " SELECT COUNT(*) ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE question_category='{$question_category}' AND category_object_id = {$object_id}";
            $sql .=" ORDER BY published_date DESC";
            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
        }        

        public static function find_by_tag($tag_id, $limit='0', $offset='-1') {
            $sql_1  = " SELECT object_id ";
            $sql_1 .= " FROM " . Tagmap::get_table_name();
            $sql_1 .= " WHERE object_type='question' AND tag_id = {$tag_id}";
            
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE question_id IN (".$sql_1.")";
            $sql .=" ORDER BY published_date DESC";
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function count_by_tag($tag_id) {
            $sql = " SELECT COUNT(*) ";
            $sql .=" FROM " . Tagmap::get_table_name();
            $sql .=" WHERE object_type='question' AND tag_id = {$tag_id}";
            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
        }
        
        public static function find_all($limit='0', $offset='-1') {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            if (($limit == 0) && ($offset == -1)){
                $sql .=" ORDER BY question_body ASC ";
            }
            if (($limit > 0) && ($offset > -1)){
                $sql .= " ORDER BY published_date DESC ";
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
                
            }
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }

        public static function count_all() {
            $sql  = " SELECT COUNT(*)";
            $sql .= " FROM " . self::$table_name;

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

        public static function find_by_id($question_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE question_id='{$question_id}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_id_array($question_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE question_id='{$question_id}' LIMIT 1");
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



        public function create() {
            global $database;
            $sql = "INSERT INTO ".self::$table_name." (";
            $sql .="user_id, question_body, question_category, category_object_id, sent_date, published_date";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->user_id) . "', '";
            $sql .= $database->escape_value($this->question_body) . "', '";
            $sql .= $database->escape_value($this->question_category) . "', '";
            $sql .= $database->escape_value($this->category_object_id) . "', '";
            $sql .= $database->escape_value($this->sent_date) . "', '";
            $sql .= $database->escape_value($this->published_date) . "')";
            if($database->query($sql)){
                $this->question_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " user_id = '" . $database->escape_value($this->user_id) ."' ";
            $sql .= ", question_body = '" . $database->escape_value($this->question_body)."' ";
            $sql .= ", question_category = '" . $database->escape_value($this->question_category)."' ";
            $sql .= ", category_object_id = '" . $database->escape_value($this->category_object_id)."' ";
            $sql .= ", sent_date = '" . $database->escape_value($this->sent_date)."' ";
            $sql .= ", published_date = '" . $database->escape_value($this->published_date)."' ";
            $sql .= " WHERE question_id = " . $this->question_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->question_id)){
                if (self::find_by_id($this->question_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>