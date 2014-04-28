<?php
    /**
    * Zubr
    * Location class file
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */

    require_once("config.php");

    class Location {
        protected static $table_name="tbl_locations";

        public $location_id;
        public $location_name;
        public $location_type; //enum: ('city', 'country', 'region', 'state')
        public $parent_id;
        
        public static function get_tablename(){
            return self::$table_name;
        }
        
        public static function find_all(){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name;
            $sql .=" ORDER BY location_type DESC, location_name";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        
        public static function find_locations($location_type='city', $parent_location_id=''){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name;
            $sql .=" WHERE location_type = '{$location_type}'";
            $sql .=" AND parent_id = {$parent_location_id}";
            $sql .=" ORDER BY location_name";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function count_locations($location_type='city', $parent_location_id=''){
            global $database;
            $sql = " SELECT COUNT(*) FROM " .self::$table_name;
            $sql .=" WHERE location_type = '{$location_type}'";
            $sql .=" AND parent_id = {$parent_location_id}";
            
            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
        }
        
        public static function find_countries_and_regions(){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name;
            $sql .=" WHERE location_type = 'country' or location_type = 'region'" ;
            $sql .=" ORDER BY location_name";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_countries(){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name;
            $sql .=" WHERE location_type = 'country'" ;
            $sql .=" AND parent_id <> '-1'" ;
            $sql .=" ORDER BY location_name";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_cities(){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name;
            $sql .=" WHERE location_type = 'city'" ;
            $sql .=" ORDER BY location_name";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }        
        public static function find_by_name($location_name='', $location_type=''){
            global $database;
            $sql = " SELECT * FROM " .self::$table_name;
            $sql .=" WHERE location_name = '{$location_name}'";
            $sql .=" AND location_type = '{$location_type}'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? array_shift($result_array) : false;
            
        }
        
        public static function find_by_id($location_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE location_id={$location_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function delete_by_id($location_id=0) {
            global $database;
            $sql  = ' DELETE FROM '.self::$table_name;
            $sql .= ' WHERE location_id ="'.$location_id.'"';
            $sql .= ' LIMIT 1';
            if($database->query($sql)){
                return true;
            } else {
                return false;
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
            $sql .="location_name, location_type, parent_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->location_name) . "', '";
            $sql .= $database->escape_value($this->location_type) . "', '";
            $sql .= $database->escape_value($this->parent_id) . "')";
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
            $sql .= "  location_name = '" . $database->escape_value($this->location_name) ."' ";
            $sql .= ", location_type = '" . $database->escape_value($this->location_type)."' ";
            $sql .= ", parent_id = '" .     $database->escape_value($this->parent_id)."' ";
            $sql .= " WHERE location_id = " . $this->location_id;
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public function save(){
            if (isset($this->location_id)){
                if (self::find_by_id($this->location_id)) $this->update();
            }
            else $this->create();
            return true;   
        }

    }

?>