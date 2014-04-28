<?php
    require_once("config.php");

    class University {

        protected static $table_name="tbl_university";
        public $university_id;
        public $short_name;
        public $long_name ;
        public $short_description;
        public $long_description;
        public $link;
        public $picture_extension;
        public $location_id;
        public $date_of_creation;
        public $management_form;    
        public $cost_of_year;   
        public $number_of_dorms;    
        public $number_of_studs;    
        public $number_of_sport;    
        public $number_of_library;
        public $number_of_terms;    
        public $alias;
        public $status;
        public $military_training;

        public static function find_by_id($university_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE university_id='{$university_id}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_random($num=2) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE status = 'published' ORDER BY RAND() LIMIT {$num}");
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_by_long_name($long_name="") {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE long_name='{$long_name}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_by_short_name($short_name="") {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE short_name='{$short_name}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function search_by_short_name($short_name="") {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE short_name LIKE '%{$short_name}%'");
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_all($limit='0', $offset='-1', $status='-1') {
            $sql = " SELECT * FROM ".self::$table_name;

            if ($status != -1){
                $sql .= " WHERE status = '".$status."'";
            }

            $sql .= " ORDER BY long_name";

            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
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

        public static function search_by_keyword($keyword = '',  $lookup_depth = '3', $parent_id = '-1', $range_min = '-1' , $range_max = '-1', $limit='0', $offset='-1'){
            //City
            $sub_sql  = ' SELECT location_id ';
            $sub_sql .= ' FROM ' . Location::get_tablename();
            $sub_sql .= ' WHERE parent_id = "' . $parent_id .'"';
            
            //Region
            $sub_sql2  = ' SELECT location_id ';
            $sub_sql2 .= ' FROM ' . Location::get_tablename();
            $sub_sql2 .= ' WHERE parent_id IN (' . $sub_sql .')';
            
            //Country
            $sub_sql3  = ' SELECT location_id ';
            $sub_sql3 .= ' FROM ' . Location::get_tablename();
            $sub_sql3 .= ' WHERE parent_id IN (' . $sub_sql2 .')';
            
            $sql  = ' SELECT DISTINCT * ';
            $sql .= ' FROM ' . self::$table_name;

            if (empty($keyword)){
                $sql .= ' WHERE 1';
            }
            else{
                $sql .= ' WHERE MATCH';
                $sql .= ' (short_name, long_name, long_description) ';
                $sql .= ' AGAINST ';
                $sql .= ' ("'. $keyword .'*" IN BOOLEAN MODE)';

            }

            $sql .= ' AND location_id IN ( ';
            $sql .= $sub_sql3;
            $sql .= ' UNION';
            $sql .= $sub_sql2;
            $sql .= ' UNION';
            $sql .= $sub_sql;
            $sql .= ' )';

            if ($range_min != '-1'){
                $sql .= ' AND ';
                $sql .= ' cost_of_year >= "'.$range_min.'"' ;
            }

            if ($range_max != '-1'){
                $sql .= ' AND ';
                $sql .= ' cost_of_year <= "'.$range_max.'"' ;
            }
            
                $sql .= ' AND status = "published" ';
            
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            $result_array = self::find_by_sql($sql);

            return !empty($result_array) ? $result_array : false;
        }

        public static function get_tablename(){
            return self::$table_name;
        }

        public static function count_by_keyword($keyword = '',  $lookup_depth = '3', $parent_id = '-1', $range_min = '-1', $range_max = '-1'){
            //Getting states from country
            $sub_sql2  = ' SELECT location_id ';
            $sub_sql2 .= ' FROM ' . Location::get_tablename();
            $sub_sql2 .= ' WHERE parent_id = "' . $parent_id .'"';

            //Getting cities from states
            $sub_sql3  = ' SELECT location_id ';
            $sub_sql3 .= ' FROM ' . Location::get_tablename();
            $sub_sql3 .= ' WHERE parent_id in (' . $sub_sql2 .')';            

            $sql  = ' SELECT COUNT(DISTINCT university_id) ';
            $sql .= ' FROM ' . self::$table_name;
            
            if (empty($keyword)){
                $sql .= ' WHERE 1';
            }
            else{
                $sql .= ' WHERE MATCH';
                $sql .= ' (short_name, long_name, long_description) ';
                $sql .= ' AGAINST ';
                $sql .= ' ("'. $keyword .'*" IN BOOLEAN MODE)';

            }
            if ($parent_id != '-1'){
                if ($lookup_depth == '3'){
                    $sql .= ' AND ';    
                    $sql .= ' location_id in (' . $sub_sql3 .')';    
                }
                elseif ($lookup_depth == '2'){
                    $sql .= ' AND ';    
                    $sql .= ' location_id in (' . $sub_sql2 .')';    
                }
                elseif ($lookup_depth == '1'){
                    $sql .= ' AND ';    
                    $sql .= ' location_id = "' . $parent_id .'"';    
                }

            }

            if ($range_min != '-1'){
                $sql .= ' AND ';
                $sql .= ' cost_of_year >= "'.$range_min.'"' ;
            }

            if ($range_max != '-1'){
                $sql .= ' AND ';
                $sql .= ' cost_of_year <= "'.$range_max.'"' ;
            }

            $sql .= ' AND status = "published" ';
            
            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
        }

        public static function count_all($status='-1'){
            $sql  = ' SELECT COUNT(*) ';
            $sql .= ' FROM ' . self::$table_name;

            if ($status > -1){
                $sql .= " WHERE status = '".$status."'";
            }

            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
        }

        public static function delete_by_id($university_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE university_id={$university_id} LIMIT 1";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public static function delete_by_alias($alias) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE alias='{$alias}' LIMIT 1";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public static function find_by_alias($alias) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE alias='{$alias}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_location_id($location_id) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE location_id='{$location_id}'");
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
            $sql .="short_name, long_name, short_description, long_description, link, picture_extension, location_id, date_of_creation, management_form, cost_of_year, number_of_terms, number_of_dorms, number_of_studs, number_of_sport, number_of_library, alias, status, military_training";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->short_name) . "', '";
            $sql .= $database->escape_value($this->long_name) . "', '";
            $sql .= $database->escape_value($this->short_description) . "', '";
            $sql .= $database->escape_value($this->long_description) . "', '";
            $sql .= $database->escape_value($this->link) . "', '";
            $sql .= $database->escape_value($this->picture_extension) . "', '";
            $sql .= $database->escape_value($this->location_id) . "', '";
            $sql .= $database->escape_value($this->date_of_creation) . "', '";
            $sql .= $database->escape_value($this->management_form) . "', '";
            $sql .= $database->escape_value($this->cost_of_year) . "', '";
            $sql .= $database->escape_value($this->number_of_terms) . "', '";
            $sql .= $database->escape_value($this->number_of_dorms) . "', '";
            $sql .= $database->escape_value($this->number_of_studs) . "', '";
            $sql .= $database->escape_value($this->number_of_sport) . "', '";
            $sql .= $database->escape_value($this->number_of_library) . "', '";
            $sql .= $database->escape_value($this->alias) . "', '";
            $sql .= $database->escape_value($this->status) . "', '";
            $sql .= $database->escape_value($this->military_training) . "')";
            if($database->query($sql)){
                $this->university_id = $database->insert_id();
                
                
                //Now let's start counitng stat for the uniersity
                
                $counter = new Stats_UniversityView;
                $counter->university_id = $this->university_id;
                $counter->date_of_start = date('Y-m-d H:i:s');
                $counter->date_of_end = date('Y-m-d H:i:s', strtotime('+1 year'));
                $counter->views = 0;
                $counter->promotion_type = 'none';
                
                if($counter->save()){
                return true;    
                };
            } else {
                return false;
            }
        }

        public function update() {

            global $database;

            $sql = "UPDATE ".self::$table_name;
            $sql .= " SET ";
            $sql .= " short_name = '" . $database->escape_value($this->short_name) ."' ";
            $sql .= ", long_name = '" . $database->escape_value($this->long_name)."' ";
            $sql .= ", short_description = '" . $database->escape_value($this->short_description)."' ";
            $sql .= ", picture_extension = '" . $database->escape_value($this->picture_extension)."' ";
            $sql .= ", long_description = '" . $database->escape_value($this->long_description)."' ";
            $sql .= ", link = '" . $database->escape_value($this->link)."' ";
            $sql .= ", location_id = '" . $database->escape_value($this->location_id)."' ";
            $sql .= ", date_of_creation = '" . $database->escape_value($this->date_of_creation)."' ";
            $sql .= ", management_form = '" . $database->escape_value($this->management_form)."' ";
            $sql .= ", cost_of_year = '" . $database->escape_value($this->cost_of_year)."' ";
            $sql .= ", number_of_terms = '" . $database->escape_value($this->number_of_terms)."' ";
            $sql .= ", number_of_dorms = '" . $database->escape_value($this->number_of_dorms)."' ";
            $sql .= ", number_of_studs = '" . $database->escape_value($this->number_of_studs)."' ";
            $sql .= ", number_of_sport = '" . $database->escape_value($this->number_of_sport)."' ";
            $sql .= ", number_of_library = '" . $database->escape_value($this->number_of_library)."' ";
            $sql .= ", alias = '" . $database->escape_value($this->alias)."' ";
            $sql .= ", status = '" . $database->escape_value($this->status)."' ";
            $sql .= ", military_training = '" . $database->escape_value($this->military_training)."' ";
            $sql .= " WHERE university_id = " . $this->university_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->university_id)){
                if (self::find_by_id($this->university_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>