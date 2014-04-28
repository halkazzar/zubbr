<?php
    /**
    * Zubr
    * Database abstraction file
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */

require_once("config.php");
require_once("functions.php");

class MySQLDatabase {
private $connection;
private $magic_quotes_active;
private $real_escape_string_exists;


function __construct() {
$this->open_connection();
$this->magic_quotes_active = get_magic_quotes_gpc();
$this->real_escape_string_exists = function_exists("mysql_real_escape_string");
}

public function open_connection() {
	$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS, false, MYSQL_CLIENT_INTERACTIVE);
	if (!$this->connection) {
	die("Database connection failed: " . mysql_error());
	} else {
	$db_select = mysql_select_db(DB_NAME, $this->connection);
	if (!$db_select) {
	die("Database selection failed: " . mysql_error());
}

}
}

public function close_connection() {
if(isset($this->connection)) {
	mysql_close($this->connection);
	unset($this->connection);
}

}

public function query($sql){
mysql_query("SET NAMES 'utf8'",$this->connection);
mysql_query("SET CHARACTER SET 'utf8'",$this->connection);
if (!mysql_ping($this->connection)) {
   log_action("DB", "Connection lost");
   $this->open_connection();
   log_action("DB", "Connection is being established again"); 
}
$result = mysql_query($sql, $this->connection);
$this->confirm_query($result, $sql);
return $result;
}

public function escape_value($value) {
if ($this->real_escape_string_exists) {
	if($this->magic_quotes_active) {$value = stripslashes( $value ); }
	$value = mysql_real_escape_string( $value );
} else {
	if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }

}
return $value;
}

private function confirm_query($result, $sql) {
if (!$result) {
    log_action($_SERVER['REQUEST_URI'], "Database query: Fail: <b>$sql</b> " . mysql_error(), 1);
    die("Database query failed: <b>$sql</b> " . mysql_error());
     
    //print_r($_SERVER);
    
    //redirect_to('/notfound/');
}
}

public function fetch_array($result_set){
return mysql_fetch_array($result_set);
}

public function num_rows($result_set){
return mysql_num_rows($result_set);
}

public function insert_id(){
return mysql_insert_id($this->connection);
}

public function affected_rows(){
return mysql_affected_rows($this->connection);
}



}

$database = new MySQLDatabase();
?>
