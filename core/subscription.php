<?php
    require_once("config.php");

    class Subscription {

        protected static $table_name="tbl_subscriptions";
        public $subscription_id;
        public $user_id;
        public $subscription_category;
        public $category_object_id;

        public static function find_by_user($user_id) {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE user_id='{$user_id}'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }        
        
        public static function find_by_user_and_category($cat, $user_id) {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE user_id='{$user_id}'";
            $sql .=" AND subscription_category='{$cat}'";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function delete_by_id($subscription_id=0) {
            global $database;
            $sql  = ' DELETE FROM '.self::$table_name;
            $sql .= ' WHERE subscription_id = "'.$subscription_id.'"';
            $sql .= ' LIMIT 1';
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        
        public static function find_by_object($subscription_category, $object_id='-1', $user_id='-1', $limit='0', $offset='-1') {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            $sql .=" WHERE subscription_category='{$subscription_category}'";
            if ($object_id != -1){
                $sql .=" AND category_object_id = '{$object_id}'";
            }
            if ($user_id > -1){
                $sql .=" AND user_id = '{$user_id}'";
            }
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }

//        public static function count_by_object($subscription_category, $object_id) {
//            $sql = " SELECT COUNT(*) ";
//            $sql .=" FROM " .self::$table_name;
//            $sql .=" WHERE subscription_category='{$subscription_category}' AND category_object_id = {$object_id}";
//            $sql .=" ORDER BY published_date DESC";
//            global $database;
//            $result_set = $database->query($sql);
//            $row = $database->fetch_array($result_set);
//            return $row[0];
//        }        
//        
        public static function find_all($limit='0', $offset='-1') {
            $sql = " SELECT * ";
            $sql .=" FROM " .self::$table_name;
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }

//        public static function count_all() {
//            $sql  = " SELECT COUNT(*)";
//            $sql .= " FROM " . self::$table_name;
//            
//            global $database;
//            $result_set = $database->query($sql);
//            $row = $database->fetch_array($result_set);
//            return $row[0];
//        }
        
        public static function find_by_sql($sql="") {
            global $database;
            $result_set = $database->query($sql);
            $object_array = array();
            while ($row = $database->fetch_array($result_set)) {
                $object_array[] = self::instantiate($row);
            }
            return $object_array;
        }

        public static function find_by_id($subscription_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE subscription_id='{$subscription_id}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
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
            $sql .="user_id, subscription_category, category_object_id";
            $sql .=") VALUES ('";
            $sql .= $database->escape_value($this->user_id) . "', '";
            $sql .= $database->escape_value($this->subscription_category) . "', '";
            $sql .= $database->escape_value($this->category_object_id) . "')";
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
            $sql .= " user_id = '" . $database->escape_value($this->user_id) ."' ";
            $sql .= ", subscription_category = '" . $database->escape_value($this->subscription_category)."' ";
            $sql .= ", category_object_id = '" . $database->escape_value($this->category_object_id)."' ";
            $sql .= " WHERE subscription_id = " . $this->subscription_id;

            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public function save(){
            if (isset($this->subscription_id)){
                if (self::find_by_id($this->subscription_id)) $this->update();
            }
            else $this->create();
            return true;   
        }
    }

?>