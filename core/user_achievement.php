<?php
    require_once ("config.php");

    class UserAchievement
    {
        protected static $table_name = "tbl_users_achiements";
        public $user_achievement_id;
        public $user_id;
        public $achievement_id;

        public static function find_achievements_by_user_id($user_id, $limit = 5){
            $sql  = " SELECT *";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE user_id = '{$user_id}'";
            $sql .= " LIMIT '{$limit}'";
            
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_achievement($achievement_id, $random = 5){
            $sql  = " SELECT *";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE achievement_id = '{$achievement_id}'";
            $sql .= " ORDER BY RAND()";
            
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }        
        
        
        
        public static function delete_by_id($university_relation_id) {
            global $database; 
            $sql = " DELETE FROM ".self::$table_name." WHERE university_relation_id='{$university_relation_id}' LIMIT 1";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
        }

        public static function find_all() {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name);
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_by_id($university_relation_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE university_relation_id={$university_relation_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_role($role = "alumni", $university_id, $limit = 10, $order=' RAND() '){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE role = '" . $role ."'";
            $sql .= " AND university_id = " . $university_id;
            $sql .= " ORDER BY " . $order; 
            $sql .= " LIMIT " . $limit;

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }        

        public static function find_by_user($user_id){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE user_id = '" . $user_id ."'";
            $sql .= " ORDER BY role DESC";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_sql($sql = "")
        {
            global $database;
            $result_set=$database->query($sql);

            $object_array=array();

            while ($row=$database->fetch_array($result_set))
            {
                $object_array[]=self::instantiate($row);
            }

            return $object_array;
        }



        private static function instantiate($record)
        {
            $object=new self;

            foreach ($record as $attribute => $value)
            {
                if ($object->has_attribute($attribute))
                {
                    $object->$attribute=$value;
                }
            }

            return $object;
        }

        private function has_attribute($attribute)
        {
            $object_vars=get_object_vars($this);
            return array_key_exists($attribute, $object_vars);
        }

        public function create()
        {
            global $database;
            $sql ="INSERT INTO " . self::$table_name . " (";
            $sql.="user_id, university_id,  date_of_enroll, date_of_graduation, degree_id, studyarea_id, scholarship_id, role";
            $sql.=") VALUES ('";
            $sql.=$database->escape_value($this->user_id) . "', '";
            $sql.=$database->escape_value($this->university_id) . "', '";
            $sql.=$database->escape_value($this->date_of_enroll) . "', '";
            $sql.=$database->escape_value($this->date_of_graduation) . "', '";
            $sql.=$database->escape_value($this->degree_id) . "', '";
            $sql.=$database->escape_value($this->studyarea_id) . "', '";
            $sql.=$database->escape_value($this->scholarship_id) . "', '";
            $sql.=$database->escape_value($this->role) . "')";

            if ($database->query($sql))
            {
                $this->university_relation_id = $database->insert_id();
                return true;
            }
            else
            {
                return false;
            }
        }

        public function update()
        {
            global $database;
            $sql="UPDATE " . self::$table_name;
            $sql.=" SET ";
            $sql.="  user_id = '" . $database->escape_value($this->user_id) . "' ";
            $sql.=", university_id = '" . $database->escape_value($this->university_id) . "' ";
            $sql.=", role = '" . $database->escape_value($this->role) . "' ";
            $sql.=", date_of_enroll = '" . $database->escape_value($this->date_of_enroll) . "' ";
            $sql.=", date_of_graduation = '" . $database->escape_value($this->date_of_graduation) . "' ";
            $sql.=", degree_id = '" . $database->escape_value($this->degree_id) . "' ";
            $sql.=", studyarea_id = '" . $database->escape_value($this->studyarea_id) . "' ";
            $sql.=", scholarship_id = '" . $database->escape_value($this->scholarship_id) . "' ";
            $sql.=" WHERE university_relation_id = " . $this->university_relation_id;

            if ($database->query($sql))
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function save(){
            if (isset($this->university_relation_id)){
                if (self::find_by_id($this->university_relation_id)) $this->update();
            }
            else $this->create();
            return true;   
        }
    }
?>
