<?php
    //Do not include config.php
    //cause it causes sending headers
    class Session {
        private $promo_id;
        private $promo_validated;
        private $online_users;
        private $logged_in;
        public  $user_id;
        public  $language;
        public  $passed_test_record_id;
        public  $paid_views =  "";
        public  $free_views =  ""; 
        public  $none_views =  ""; 

        public function get_online_users(){
            session_start();
            if($dir_handle = opendir(session_save_path())){
                $count = 0;
                while(false !== ($file = readdir($dir_handle))){
                    if($file != '.' && $file != '..'){
                        if(($diff = time() - fileatime(session_save_path() . '/' . $file)) < 120 ){
                            $count++;
                        }                    
                    }
                }
                closedir($dir_handle);
                return $count;
            }else{
                return false;
            }
        }

        function __construct() {
            session_start();
            $this->check_login();

        }

        private function check_login() {
            if(!empty($_SESSION['passed_test_record_id'])){
                $this->passed_test_record_id = $_SESSION['passed_test_record_id'];
            }


            if(isset($_SESSION['id_user'])) {
                $this->user_id = $_SESSION['id_user'];
                $this->logged_in = true;
                if(!empty($_SESSION['language'])){
                    $this->language = $_SESSION['language']; 
                }
                if(!empty($_SESSION['paid_views'])){
                    $this->paid_views = $_SESSION['paid_views']; 

                }
                if(!empty($_SESSION['free_views'])){
                    $this->free_views = $_SESSION['free_views']; 

                }
                if(!empty($_SESSION['none_views'])){
                    $this->none_views = $_SESSION['none_views'];

                }


            } else {
                unset($this->user_id);
                unset($this->language); 
                unset($this->paid_views); 
                unset($this->free_views); 
                unset($this->none_views); 
                //unset($this->passed_test_record_id);
                $this->logged_in = false;
            }
        }

        public function save_test_record($id){
            $this->passed_test_record_id = $_SESSION['passed_test_record_id'] = $id; 
        }

        public function is_logged_in() {
            return $this->logged_in;
        }

        public function is_promo_validataed() {
            if(isset($_SESSION['promo_validated']))
                return true;
        }

        public function get_promo(){
            return $_SESSION['promo_id'];
        }

        public function login_promo($promocode){
            if($promocode) {
                $this->promo_validated = $_SESSION['promo_validated'] = true;    
                $this->promo_id = $_SESSION['promo_id'] = $promocode->promocode_id;    
            }    
        }

        public function logout_promo($promocode){
            if($promocode) {
                $this->promo_validated = false;    
            }    
        }

        public function login($user) {
            if($user) {
                $this->user_id = $_SESSION['id_user'] = $user->usr_id;

                if(!empty($_SESSION['passed_test_record_id'])){
                    $passed_test = Passed_test::find_by_id($_SESSION['passed_test_record_id']);
                    if(!empty($passed_test)){
                        $passed_test->user_id = $user->usr_id;
                        $passed_test->save(); 
                    }    
                }

                //$this->passed_test_record_id = $_SESSION['passed_test_record_id'] = Test::get_passed_test_record(); 
                $this->language = $_SESSION['language'] = 'ru';


                //E_ERROR ALL 
                //IF STAT COUNTER DOESN't WORK as it should
                //then it is due to $_SESSION['paid_views'] = 0;
                //just remove the '=0';

                $this->paid_views = $_SESSION['paid_views'] = 0;
                $this->free_views = $_SESSION['free_views'] = 0;
                $this->none_views = $_SESSION['none_views'] = 0;
                //if($user->language <> '') {$this->language = $_SESSION['language'] = $user->language;} 
                //else 
                //{$this->language = $_SESSION['language'] = 'en';} 
                $this->logged_in = true;
            }
        }

        public function update_view($alias, $promo_type){
            if ($promo_type == 'paid') {
                $this->paid_views .= '_' . $alias;
                $_SESSION['paid_views'] .= '_' . $alias;
            }

            elseif ($promo_type == 'free') {
                $this->free_views .= '_' . $alias;
                $_SESSION['free_views'] .= '_' . $alias;
            }
            elseif ($promo_type == 'none') {
                $this->none_views .= '_' . $alias;
                $_SESSION['none_views'] .= '_' . $alias;
            }

            return true;       
        }

        public function is_alias_in_session($alias, $promo_type){
            if (self::is_logged_in()){
                if ($promo_type == 'paid'){
                    $position = strpos($this->paid_views, $alias);
                }
                elseif ($promo_type == 'free'){
                    $position = strpos($this->free_views, $alias);
                }
                elseif ($promo_type == 'none'){
                    $position = strpos($this->none_views, $alias);
                }
                if ($position == false)
                    return false; else return true;    
            }else{
                //User is not logged in, then we shouldn't count it's view
                return true; //Which means not to increment the counter    
            }

        }

        public function logout() {
            unset($_SESSION['id_user']);
            unset($_SESSION['language']);
            unset($_SESSION['paid_views']);
            unset($_SESSION['free_views']);
            unset($_SESSION['none_views']);
            unset($this->user_id);
            unset($this->language);
            unset($this->paid_views);
            unset($this->free_views);
            unset($this->none_views);
            $this->logged_in = false;
        }
    }
    if (!isset($session)) $session = new Session();

?>