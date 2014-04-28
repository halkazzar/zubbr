<?php
    require_once ("config.php");

    class Relation
    {
        protected static $table_name = "tbl_university_relations";
        public $university_relation_id;
        public $user_id;
        public $role;
        public $date_of_enroll;
        public $date_of_graduation;
        public $degree_id;
        public $studyarea_id;
        public $university_id;
        public $scholarship_id;

        public static function get_table_name(){
            return self::$table_name;
        }
        
        public static function find_iwantostudy_universities($user_id = -1){
            $sql  = " SELECT university_id, COUNT( * ) ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE role = 'abitur'";
            if ($user_id != -1) $sql .= " AND user_id = '{$user_id}'";
            $sql .= " GROUP BY university_id";
            $sql .= " ORDER BY COUNT( * ) DESC";
            $sql .= " LIMIT 10";
            
            global $database;
            $result_set = $database->query($sql);
            $object_array = array();
            while ($row = $database->fetch_array($result_set)) {
                $object_array[] = $row;
            }
            return $object_array;
        }
        
        public static function find_whowantstostudywithme_universities($user_id, $limit=5){
            $sub_sql  = " SELECT DISTINCT university_id";
            $sub_sql .= " FROM " .self::$table_name;
            $sub_sql .= " WHERE role = 'abitur'";
            $sub_sql .= " AND user_id = '{$user_id}'";
            
            $sql  = " SELECT *";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE role = 'abitur'";
            $sql .= " AND university_id in ({$sub_sql})";
            $sql .= " AND user_id <> {$user_id}";        //made to exclude putting user himself
            $sql .= " LIMIT {$limit}";        
            
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

        public static function find_all($distinct_field='') {
            $sql = "SELECT * FROM ".self::$table_name;
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_all_distinct() {
            $sql  = " SELECT * FROM ".self::$table_name;
            $sql .= " GROUP BY user_id";
            $sql .= " ORDER BY university_id";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        public static function find_by_id($university_relation_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE university_relation_id='{$university_relation_id}' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_role($role = "alumni", $university_id, $limit = 10, $order=' RAND() '){
            $sql  = " SELECT DISTINCT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE role = '" . $role ."'";
            $sql .= " AND university_id = " . $university_id;
            $sql .= " GROUP BY user_id";
            $sql .= " ORDER BY " . $order;
            if($limit != -1){
            $sql .= " LIMIT " . $limit;
            }
             
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }        

        public static function find_by_all($role = "alumni", $university_id, $user_id){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE role = '" . $role ."'";
            $sql .= " AND university_id = " . $university_id;
            $sql .= " AND user_id = " .$user_id; 
            $sql .= " LIMIT 1";

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
        
        public static function find_by_university($uni_id){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE university_id = '" . $uni_id ."'";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_degree($degree_id){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE degree_id = '" . $degree_id ."'";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_scholarship($scholarship_id){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE scholarship_id = '" . $scholarship_id ."'";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_studyarea($studyarea_id){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE studyarea_id = '" . $studyarea_id ."'";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_by_user_and_role($role, $user_id){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE user_id = '" . $user_id ."'";
            $sql .= " AND role = '" . $role ."'";
            $sql .= " ORDER BY role DESC";

            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function find_my_unis($user_id){
            $sql  = " SELECT * ";
            $sql .= " FROM " . self::$table_name;
            $sql .= " WHERE user_id = '" . $user_id ."'";
            $sql .= " AND role in ('student', 'alumni')";
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