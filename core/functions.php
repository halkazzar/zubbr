<?php
    /**
    * Zubr
    * Basic functions file
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */

    function n_symbols($string, $num){
        
            $result = mb_substr($string, 0, $num);
            $to_be_del = mb_strrchr($result, ' ');
        
            $arr = explode($to_be_del, $result);
            return $arr[0] . ' ';
        
    }
    
    function translit($string){
        
    
    $trans = array("а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e", "ё"=>"yo","ж"=>"j","з"=>"z","и"=>"i","й"=>"i","к"=>"k","л"=>"l", "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t", "у"=>"u","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch", "ш"=>"sh","щ"=>"sh","ы"=>"i","э"=>"e","ю"=>"u","я"=>"ya",
  "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E", "Ё"=>"Yo","Ж"=>"J","З"=>"Z","И"=>"I","Й"=>"I","К"=>"K", "Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P", "Р"=>"R","С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F", "Х"=>"H","Ц"=>"C","Ч"=>"Ch","Ш"=>"Sh","Щ"=>"Sh", "Ы"=>"I","Э"=>"E","Ю"=>"U","Я"=>"Ya",
  "ь"=>"","Ь"=>"","ъ"=>"","Ъ"=>"");
  return strtr($string, $trans);
  } 



    function dateSort($f1,$f2)
    {
        if ($f1->date_of_publish == $f2->date_of_publish) {
            return 0;
        }
        return ($f1->date_of_publish < $f2->date_of_publish) ? 1 : -1;
    }




    function randompassword($count){
        $pass = str_shuffle('abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#%$*');
        return substr($pass, 3, $count);
    }

    function echo_message($code){
        if (!empty($code)){
            global $_Errors;
            if ($code<=0)
                echo "<div class='success'>". $_Errors[$code]."</div>";
            else
                echo "<div class='error'>".$_Errors[$code]."</div>";
        }
    }

    function log_action($action, $message="", $type="0", $user="guest") {
        $logfile = $_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt';
        $new = file_exists($logfile) ? false : true;
        if($handle = fopen($logfile, 'a')) { // append
            $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());

            switch ($type) {
                case 0:
                    $type = 'i';
                    break;
                case 1:
                    $type = '*';
                    break;
            }

            if($user == "guest"){
                $user = GetRealIp();
            }


            $content = "{$type}\t{$timestamp}\t{$user}\t{$action}: {$message}\n";
            fwrite($handle, $content);
            fclose($handle);
            if($new) { chmod($logfile, 0755); }
        } else {
            echo "Could not open log file for writing.";
        }
    } 

    function strip_zeros_from_date( $marked_string="" ) {
        // first remove the marked zeros
        $no_zeros = str_replace('*0', '', $marked_string);
        // then remove any remaining marks
        $cleaned_string = str_replace('*', '', $no_zeros);
        return $cleaned_string;
    }

    function n_words($string, $n){
        $tmp = explode(' ', $string, $n+1);

        $result = '';

        if (count($tmp) < $n){
            $n = count($tmp);
        };

        for($i = 0; $i < $n; $i++){
            $result .= ' ' . $tmp[$i];
        }        
        return $result . '...';
    }

    //redirect_to uses to redirect user
    function redirect_to( $location = NULL ) {
        if ($location != NULL) {
            header("Location: {$location}");
            exit;
        }
    }

    function is_odd($number) {
        return($number & 1); 
    }

    function is_even($number) {
        return(!($number & 1));
    }

    function date_translate($date, $format='format'){
        $russian_days = array('Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота');
        $russian_months = array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
        $russian_months2 = array('Января','Февраля','Марта','Апреля','Мая','Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря');

        if ($date=='today'){
            $today = getdate();
            if ($format=="top"){
                return $russian_days[$today["wday"]].', '.$russian_months[$today["mon"]-1].' '.$today["mday"].', '.$today["year"];
            }
        }
        else{
            $date1 = str_replace(' ','-',$date);
            $date1 = str_replace(':','-',$date1);
            list($year, $month, $day, $hour, $minute, $second) = explode('-', $date1);
            $array['sec'] = $second;
            $array['min'] = $minute;
            $array['hour'] = $hour;
            $array['day'] = $day;
            $temp = getdate(mktime($hour, $minute, $second, $month, $day, $year)); 
            $array['day_word'] = $russian_days[$temp["wday"]];
            $array['month'] = $month;
            $array['month_word'] = $russian_months[$month - 1];
            $array['month_word2'] = $russian_months2[$month - 1];
            $array['year'] = $year;
            return $array;
        }

    }

    //date_long_ago determines the difference between the given date and today
    //returns pretty text like 'только что, недавно, полчаса назад, Х часов назад'
    function date_long_ago($date){
        $russian_months = array('Января','Февраля','Марта','Апреля','Мая','Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря');

        $today = getdate();
        $date = str_replace(' ','-',$date);
        $date = str_replace(':','-',$date);
        list($year, $month, $day, $hour, $minute, $second) = explode('-', $date);


        //Dates should be converted to seconds, to subtract correctly
        //current version has a bug when i.e. 24-01  -  23-59  it says: 'час назад', 
        //but should say 'только что'
        if ($year == $today["year"]){
            if ($month == $today["mon"]){
                if ($day == $today["mday"]){
                    if ($hour == $today["hours"]){
                        if (($today["minutes"] - $minute) < 6){
                            $result = 'только что';
                        }
                        elseif (($today["minutes"] - $minute) < 16){
                            $result = 'недавно';
                        }
                        elseif (($today["minutes"] - $minute) < 31){
                            $result = 'полчаса назад';
                        }
                        elseif (($today["minutes"] - $minute) < 61){
                            $result = $today["minutes"] - $minute . ' минут назад';
                        }
                    }
                    elseif (($today["hours"] - $hour) == 1){
                        $result = 'час назад';
                    }
                    elseif (((2 <= ($today["hours"] - $hour)) && (($today["hours"] - $hour) < 5))){
                        $result = $today["hours"] - $hour . ' часа назад';
                    }
                    elseif ((( 5 <= $today["hours"] - $hour)) && (($today["hours"] - $hour) < 21)){
                        $result = $today["hours"] - $hour . ' часов назад';
                    }
                    elseif (($today["hours"] - $hour) == 21){
                        $result = $today["hours"] - $hour . ' час назад';
                    }
                    elseif (($today["hours"] - $hour) < 25){
                        $result = $today["hours"] - $hour . ' час назад';
                    }
                }
                elseif (($today["mday"] - $day) == 1){
                    $result = 'вчера';
                }
                elseif (($today["mday"] - $day) == 1){
                    $result = 'вчера';
                }
                elseif (((2 <= ($today["mday"] - $day)) && (($today["mday"] - $day) < 5))){
                    $result = $today["mday"] - $day . ' дня назад';
                }
                elseif ((( 5 <= $today["mday"] - $day)) && (($today["mday"] - $day) < 21)){
                    $result = $today["mday"] - $day . ' дней назад';
                }
                elseif (($today["mday"] - $day) == 21){
                    $result = $today["mday"] - $day . ' день назад';
                }
                elseif (($today["mday"] - $day) < 32){
                    $result = $today["mday"] - $day . ' дней назад';
                }
            }
            else $result = $day .' '. $russian_months[$month - 1] .' '. $year;  
        }
        else $result = $day .' '. $russian_months[$month - 1] .' '. $year;    

        //   $today = date($dateformat);
        if (($day == 0) || ($month == 0) || ($year == 0)) {$result = 'самого начала';};
        return $result;
    }


    function shorten_string($content, $wordsreturned) {
        /*  Returns the first $wordsreturned out of $content.  If string
        contains fewer words than $wordsreturned, the entire string
        is returned.
        */
        $string = str_replace('<p>&nbsp;</p>', ' ', $string);
        $string = html_entity_decode(strip_tags($content));

        $retval = $string;
        $array = explode(" ", $string);
        if (count($array)<=$wordsreturned)
        /*  Already short enough, return the whole thing
        */
        {
            $retval = $string;
        }
        else
        /*  Need to chop of some words
        */
        {
            array_splice($array, $wordsreturned);
            $retval = implode(" ", $array)." ...";
        }
        return $retval;
    }

    function close_dangling_tags($html){
        $single_tags = array('meta','img','br','link','area','input','hr','col','param','base');
        preg_match_all('~<([a-z0-9]+)(?: .*)?(?<![/|/ ])>~iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('~</([a-z0-9]+)>~iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $single_tags)) {
                if (FALSE !== ($key = array_search($openedtags[$i], $closedtags))) {
                    unset($closedtags[$key]);
                }
                else {
                    $html .= '</'.$openedtags[$i].'>';
                }
            }
        }
        return $html;

    }


    function tag_str_to_array($tags_string=''){
        $pattern1 = '/, *,/';
        $pattern2 = '/, *$/';

        $tags_string = preg_replace($pattern1, '', $tags_string);
        $tags_string = preg_replace($pattern2, '', $tags_string);

        $tag_names = explode(',', $tags_string);
        return $tag_names;
    }

    function GetRealIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    //facebook related functions

    function parse_signed_request($signed_request, $secret) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

        // decode the data
        $sig = base64_url_decode($encoded_sig);
        $data = json_decode(base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    function get_facebook_cookie($app_id, $application_secret) {
        if(isset($_COOKIE['fbs_' . $app_id])){
            $args = array();
            parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
            ksort($args);
            $payload = '';
            foreach ($args as $key => $value) {
                if ($key != 'sig') {
                    $payload .= $key . '=' . $value;
                }
            }
            if (md5($payload . $application_secret) != $args['sig']) {
                return null;
            }
            return $args;
        }
    }



    //VKontakte functions
    function authOpenAPIMember() {
        $session = array();
        $member = FALSE;
        $valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
        if(!empty($_COOKIE['vk_app_'. VKONTAKTE_APP_ID])){
        $app_cookie = $_COOKIE['vk_app_'. VKONTAKTE_APP_ID];    
        if ($app_cookie) {
            $session_data = explode ('&', $app_cookie, 10);
            foreach ($session_data as $pair) {
                list($key, $value) = explode('=', $pair, 2);
                if (empty($key) || empty($value) || !in_array($key, $valid_keys)) {
                    continue;
                }
                $session[$key] = $value;
            }
            foreach ($valid_keys as $key) {
                if (!isset($session[$key])) return $member;
            }
            ksort($session);

            $sign = '';
            foreach ($session as $key => $value) {
                if ($key != 'sig') {
                    $sign .= ($key.'='.$value);
                }
            }
            $sign .= VKONTAKTE_SECRET ;
            $sign = md5($sign);
            if ($session['sig'] == $sign && $session['expire'] > time()) {
                $member = array(
                'id' => intval($session['mid']),
                'secret' => $session['secret'],
                'sid' => $session['sid']
                );
            }
        }
        }
        
        
        
        return $member;
    }

    //Mail.ru FUNCTIONS
    function sign_server_server(array $request_params, $secret_key) {
        ksort($request_params);
        $params = '';
        foreach ($request_params as $key => $value) {
            $params .= "$key=$value";
        }
        return md5($params . $secret_key);
    }
?>
