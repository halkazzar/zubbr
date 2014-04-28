<?php
    require_once("config.php");

    class Stats_UniversityView {

        protected static $table_name="tbl_stats_university_views";
        public $stats_university_views_id;
        public $university_id;
        public $promotion_type;
        public $views;
        public $date_of_start;
        public $date_of_end;

        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY date_of_end DESC");
            return !empty($result_array) ? $result_array : false;
        }
        
        
        public static function find_all_promoted($type = 'paid', $university_id = "-1"){
                $subsql  = " (SELECT university_id ";
                $subsql .= " FROM " . University::get_tablename();
                $subsql .= " WHERE status = 'published')";
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE date_of_end >= CURDATE()";
            $sql .= " AND   date_of_start <= CURDATE()";
            $sql .= " AND promotion_type = '{$type}'";
            $sql .= " AND university_id IN " . $subsql;
            if (($type = 'none') && ($university_id != "-1")) $sql .= " AND university_id = {$university_id}";
            if (($type = 'none') && ($university_id != "-1")) $sql .= " LIMIT 1";
            if (($type = 'none') && ($university_id == "-1")) $sql .= " ORDER BY views DESC LIMIT 10";
            
            $result_array = self::find_by_sql($sql);
            if ($university_id != "-1") {
                return !empty($result_array) ? array_shift($result_array) : false;
            }
            else {
                return !empty($result_array) ? $result_array : false;     
            }
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

        public static function find_by_id($stats_university_views_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE stats_university_views_id={$stats_university_views_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        
        public static function delete_by_id($stats_university_views_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE stats_university_views_id={$stats_university_views_id} LIMIT 1";
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
            $sql .="university_id, promotion_type, views, date_of_start, date_of_end";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->university_id) . "', '";
            $sql .= $database->escape_value($this->promotion_type) . "', '";
            $sql .= $database->escape_value($this->views) . "', '";
            $sql .= $database->escape_value($this->date_of_start) . "', '";
            $sql .= $database->escape_value($this->date_of_end) . "')";
            if($database->query($sql)){
                $this->stats_university_views_id = $database->insert_id();
                return true;
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " university_id = '" . $database->escape_value($this->university_id) ."' ";
            $sql .= ", promotion_type = '" . $database->escape_value($this->promotion_type)."' ";
            $sql .= ", views = '" . $database->escape_value($this->views)."' ";
            $sql .= ", date_of_start = '" . $database->escape_value($this->date_of_start)."' ";
            $sql .= ", date_of_end = '" . $database->escape_value($this->date_of_end)."' ";
            $sql .= " WHERE stats_university_views_id = " . $this->stats_university_views_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->stats_university_views_id)){
                if (self::find_by_id($this->stats_university_views_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>