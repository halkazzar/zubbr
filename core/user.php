<?php
    require_once ("config.php");

    class User
    {
        protected static $table_name = "tbl_users_accounts";
        public $usr_id;
        public $login;
        public $password;
        public $email;
        public $last_visit;
        public $system_role;
        public $first_name;
        public $last_name;
        public $location_id;
        public $date_of_birth;
        public $date_of_join;
        public $picture_extension;
        public $pass_remind;
        public $email_activated;
        public $facebook_status;
        public $mailru_status;
        public $vkontakte_status;
        public $came_from_facebook;
        public $came_from_mailru;
        public $came_from_vkontakte;
        public $vkontakte_id;
        public $vkontakte_email;
        
        
        public static function is_login_free($login)
        {
            global $database;
            $login=$database->escape_value($login);

            $sql =" SELECT COUNT(*) FROM " . self::$table_name;
            $sql.=" WHERE login = '{$login}' ";

            $result=$database->query($sql);
            $result=array_shift($database->fetch_array($result));
            return ($result == 0) ? true : false;
        }

        public static function is_email_free($email = "")
        {
            global $database;
            $email=$database->escape_value($email);

            $sql  =" SELECT COUNT(*) FROM " . self::$table_name;
            $sql.=" WHERE email = '{$email}' ";
            $result=$database->query($sql);
            $result=array_shift($database->fetch_array($result));
            return ($result == 0) ? true : false;
        }

        public function is_email_activated()
        {
            if($this->email_activated == '1') 
                return true;
            else{
                return false;
            }
        }
        
        public function activate_email()
        {
            $this->email_activated = '1';
            $this->save();
            return true;
        }
        
        public function deactivate_email()
        {
            $this->email_activated = '0';
            $this->save();
            return true;
        }
        
        public function new_email_activation()
        {
            $this->email_activated = md5((randompassword(4)));
            $this->save();
            return true;
        }
        
        
        public static function authenticate($login = "", $password = "")
        {
            global $database;
            $login=$database->escape_value($login);
            $password=$database->escape_value($password);

            $sql     =" SELECT * FROM " . self::$table_name;
            $sql.=" WHERE login = '{$login}' ";
            $sql.=" AND password = '" . md5($password) . "'";
            $sql.=" LIMIT 1";
            $result_array=self::find_by_sql($sql);
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_recent_users($limit = 10){
            $sql  = " SELECT * FROM " . self::$table_name;
            $sql .= " ORDER BY date_of_join DESC ";
            $sql .= " LIMIT {$limit}";
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        //public static function find_recent_users($now, $before) {
//            
//            $sql     =" SELECT * FROM " . self::$table_name;
//            $sql.=" WHERE date_of_join < '{$now}' ";
//            $sql.=" AND date_of_join > '{$before}' ";
//            
//            $result_array = self::find_by_sql($sql);
//            return !empty($result_array) ? $result_array : false;
//        }
//        
        public static function find_all_users($limit='0', $offset='-1') {
            $sql = "SELECT * FROM ".self::$table_name." ORDER BY login";
            
            if (($limit > 0) && ($offset > -1)){
                $sql .= " LIMIT " . $limit;
                $sql .= " OFFSET " . $offset;
            }
            $result_array = self::find_by_sql($sql);
            return !empty($result_array) ? $result_array : false;
        }
        
        public static function count_all(){
            $sql  = ' SELECT COUNT(*) ';
            $sql .= ' FROM ' . self::$table_name;
            
            global $database;
            $result_set = $database->query($sql);
            $row = $database->fetch_array($result_set);
            return $row[0];
        }
        
        public static function find_all($keyword="") {
            if (!empty($keyword)){
                $sql  = " SELECT * ";
                $sql .= " FROM " . self::$table_name;
                $sql .= " WHERE login   LIKE '%" . $keyword . "%'";
                $sql .= " OR email      LIKE '%" . $keyword . "%'";
                $sql .= " OR first_name LIKE '%" . $keyword . "%'";
                $sql .= " OR last_name  LIKE '%" . $keyword . "%'";
                $sql .= " ORDER BY login ";
                $result_array = self::find_by_sql($sql);
            }
            else{
                $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY login");
            }
            return !empty($result_array) ? $result_array : false;
        }

        public static function find_by_login($login) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE login = '".$login."' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function find_by_vkontakte_id($id) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE vkontakte_id = '".$id."' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }

        public static function find_by_email($email) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE email = '".$email."' LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        public static function delete_by_login($login) {
            global $database; 
            $sql = "DELETE FROM ".self::$table_name." WHERE login='{$login}' LIMIT 1";
            if($database->query($sql)){
                return true;
            } else {
                return false;
            }
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

        public static function find_by_id($user_id=0) {
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE usr_id={$user_id} LIMIT 1");
            return !empty($result_array) ? array_shift($result_array) : false;
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
            $sql="INSERT INTO " . self::$table_name . " (";
            $sql
            .="login, password, email, last_visit, system_role, first_name, last_name, location_id, date_of_birth, date_of_join, picture_extension, pass_remind, email_activated, came_from_facebook, facebook_status, came_from_mailru, mailru_status, came_from_vkontakte, vkontakte_status, vkontakte_id, vkontakte_email";
            $sql.=") VALUES ('";
            $sql.=$database->escape_value($this->login) . "', '";
            $sql.=$database->escape_value($this->password) . "', '";
            $sql.=$database->escape_value($this->email) . "', '";
            $sql.=$database->escape_value($this->last_visit) . "', '";
            $sql.=$database->escape_value($this->system_role) . "', '";
            $sql.=$database->escape_value($this->first_name) . "', '";
            $sql.=$database->escape_value($this->last_name) . "', '";
            $sql.=$database->escape_value($this->location_id) . "', '";
            $sql.=$database->escape_value($this->date_of_birth) . "', '";
            $sql.=$database->escape_value($this->date_of_join) . "', '";
            $sql.=$database->escape_value($this->picture_extension) . "', '";
            $sql.=$database->escape_value($this->pass_remind) . "', '";
            $sql.=$database->escape_value($this->email_activated) . "', '";
            $sql.=$database->escape_value($this->came_from_facebook) . "', '";
            $sql.=$database->escape_value($this->facebook_status) . "', '";
            $sql.=$database->escape_value($this->came_from_mailru) . "', '";
            $sql.=$database->escape_value($this->mailru_status) . "', '";
            $sql.=$database->escape_value($this->came_from_vkontakte) . "', '";
            $sql.=$database->escape_value($this->vkontakte_status) . "', '";
            $sql.=$database->escape_value($this->vkontakte_id) . "', '";
            $sql.=$database->escape_value($this->vkontakte_email) . "')";

            if ($database->query($sql))
            {
                $this->usr_id = $database->insert_id();
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
            if (!empty($this->password)){
                $sql.=" password = '" . $database->escape_value($this->password) . "',  ";    
            } 
            $sql.="  email = '" . $database->escape_value($this->email) . "' ";
            $sql.=", last_visit = '" . $database->escape_value($this->last_visit) . "' ";
            $sql.=", system_role = '" . $database->escape_value($this->system_role) . "' ";
            $sql.=", first_name = '" . $database->escape_value($this->first_name) . "' ";
            $sql.=", last_name = '" . $database->escape_value($this->last_name) . "' ";
            $sql.=", location_id = '" . $database->escape_value($this->location_id) . "' ";
            $sql.=", date_of_birth = '" . $database->escape_value($this->date_of_birth) . "' ";
            $sql.=", date_of_join = '" . $database->escape_value($this->date_of_join) . "' ";
            $sql.=", picture_extension = '" . $database->escape_value($this->picture_extension) . "' ";
            $sql.=", pass_remind = '" . $database->escape_value($this->pass_remind) . "' ";
            $sql.=", email_activated = '" . $database->escape_value($this->email_activated) . "' ";
            $sql.=", came_from_facebook = '" . $database->escape_value($this->came_from_facebook) . "' ";
            $sql.=", facebook_status = '" . $database->escape_value($this->facebook_status) . "' ";
            $sql.=", came_from_mailru = '" . $database->escape_value($this->came_from_mailru) . "' ";
            $sql.=", mailru_status = '" . $database->escape_value($this->mailru_status) . "' ";
            $sql.=", came_from_vkontakte = '" . $database->escape_value($this->came_from_vkontakte) . "' ";
            $sql.=", vkontakte_status = '" . $database->escape_value($this->vkontakte_status) . "' ";
            $sql.=", vkontakte_id = '" . $database->escape_value($this->vkontakte_id) . "' ";
            $sql.=", vkontakte_email = '" . $database->escape_value($this->vkontakte_email) . "' ";
            $sql.=" WHERE usr_id = " . $this->usr_id;

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
            if (isset($this->usr_id)){
                if (self::find_by_id($this->usr_id)) $this->update();
            }
            else $this->create();
            return true;   
        }
    }
?>