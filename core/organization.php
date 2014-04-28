<?php
    require_once("config.php");

    class Organization {

        protected static $table_name="tbl_organizations";
        public $organization_id;
        public $short_name;
        public $long_name ;
        public $short_description;
        public $long_description;
        public $picture_extension;
        public $alias;

        public static function find_random($num=2) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY RAND() LIMIT {$num}");
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY long_name");
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

        public static function find_by_id($organization_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE organization_id={$organization_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        //public static function search_by_keyword($keyword = '',  $lookup_depth = '3', $parent_id = '-1', $range_min = '-1' , $range_max = '-1', $limit='0', $offset='-1'){
            //Getting states from country
//            $sub_sql2  = ' SELECT location_id ';
//            $sub_sql2 .= ' FROM ' . Location::get_tablename();
//            $sub_sql2 .= ' WHERE parent_id = "' . $parent_id .'"';

            //Getting cities from states
//            $sub_sql3  = ' SELECT location_id ';
//            $sub_sql3 .= ' FROM ' . Location::get_tablename();
//            $sub_sql3 .= ' WHERE parent_id in (' . $sub_sql2 .')';            
//            
//            $sql  = ' SELECT DISTINCT * ';
//            $sql .= ' FROM ' . self::$table_name;
//            $sql .= ' WHERE MATCH';
//            $sql .= ' (short_name, long_name, long_description) ';
//            $sql .= ' AGAINST ';
//            $sql .= ' ("'. $keyword .'*" IN BOOLEAN MODE)';
//            
//            if ($parent_id != '-1'){
//                if ($lookup_depth == '3'){
//                $sql .= ' AND ';    
//                $sql .= ' location_id in (' . $sub_sql3 .')';    
//                }
//                elseif ($lookup_depth == '2'){
//                $sql .= ' AND ';    
//                $sql .= ' location_id in (' . $sub_sql2 .')';    
//                }
//                elseif ($lookup_depth == '1'){
//                $sql .= ' AND ';    
//                $sql .= ' location_id = "' . $parent_id .'"';    
//                }
//            }
//            
//            if ($range_min != '-1'){
//                $sql .= ' AND ';
//                $sql .= ' cost_of_year >= "'.$range_min.'"' ;
//            }
//            
//            if ($range_max != '-1'){
//                $sql .= ' AND ';
//                $sql .= ' cost_of_year <= "'.$range_max.'"' ;
//            }
            //echo $sql;
//            if (($limit > 0) && ($offset > -1)){
//                $sql .= " LIMIT " . $limit;
//                $sql .= " OFFSET " . $offset;
//            }
//            $result_array = self::find_by_sql($sql);
//            
//            return !empty($result_array) ? $result_array : false;
//        }
//        
        
        //public static function count_by_keyword($keyword = '',  $lookup_depth = '3', $parent_id = '-1', $range_min = '-1', $range_max = '-1'){
            //Getting states from country
//            $sub_sql2  = ' SELECT location_id ';
//            $sub_sql2 .= ' FROM ' . Location::get_tablename();
//            $sub_sql2 .= ' WHERE parent_id = "' . $parent_id .'"';

            //Getting cities from states
//            $sub_sql3  = ' SELECT location_id ';
//            $sub_sql3 .= ' FROM ' . Location::get_tablename();
//            $sub_sql3 .= ' WHERE parent_id in (' . $sub_sql2 .')';            
//            
//            $sql  = ' SELECT COUNT(DISTINCT organization_id) ';
//            $sql .= ' FROM ' . self::$table_name;
//            $sql .= ' WHERE MATCH';
//            $sql .= ' (short_name, long_name, long_description) ';
//            $sql .= ' AGAINST ';
//            $sql .= ' ("'. $keyword .'*" IN BOOLEAN MODE)';
//            
//            if ($parent_id != '-1'){
//                if ($lookup_depth == '3'){
//                $sql .= ' AND ';    
//                $sql .= ' location_id in (' . $sub_sql3 .')';    
//                }
//                elseif ($lookup_depth == '2'){
//                $sql .= ' AND ';    
//                $sql .= ' location_id in (' . $sub_sql2 .')';    
//                }
//                elseif ($lookup_depth == '1'){
//                $sql .= ' AND ';    
//                $sql .= ' location_id = "' . $parent_id .'"';    
//                }
//                    
//            }
//            
//            if ($range_min != '-1'){
//                $sql .= ' AND ';
//                $sql .= ' cost_of_year >= "'.$range_min.'"' ;
//            }
//            
//            if ($range_max != '-1'){
//                $sql .= ' AND ';
//                $sql .= ' cost_of_year <= "'.$range_max.'"' ;
//            }
//            
//            global $database;
//            $result_set = $database->query($sql);
//            $row = $database->fetch_array($result_set);
//            return $row[0];
//        }
//        
        public static function delete_by_id($organization_id) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE organization_id={$organization_id} LIMIT 1";
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

        //public static function find_by_location_id($location_id) {
//            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE location_id='{$location_id}'");
//            return !empty($result_array) ? $result_array : false;
//        }
        
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
            $sql .="short_name, long_name, short_description, long_description, picture_extension, alias";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->short_name) . "', '";
            $sql .= $database->escape_value($this->long_name) . "', '";
            $sql .= $database->escape_value($this->short_description) . "', '";
            $sql .= $database->escape_value($this->long_description) . "', '";
            $sql .= $database->escape_value($this->picture_extension) . "', '";
            $sql .= $database->escape_value($this->alias) . "')";
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
            $sql .= " short_name = '" . $database->escape_value($this->short_name) ."' ";
            $sql .= ", long_name = '" . $database->escape_value($this->long_name)."' ";
            $sql .= ", short_description = '" . $database->escape_value($this->short_description)."' ";
            $sql .= ", picture_extension = '" . $database->escape_value($this->picture_extension)."' ";
            $sql .= ", long_description = '" . $database->escape_value($this->long_description)."' ";
            $sql .= ", alias = '" . $database->escape_value($this->alias)."' ";
            $sql .= " WHERE organization_id = " . $this->organization_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->organization_id)){
                if (self::find_by_id($this->organization_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>