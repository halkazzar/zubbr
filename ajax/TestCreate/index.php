<?
//Description: test create
//THIS IS AJAX FILE
//Directory: /ajax/TestCreate/index.php
//
//Created: by Mus on Feb 1, 2010
//
//Last Edit: by Mus on Feb 1, 2010
//


session_start();

require_once "/usr/local/pem/vhosts/107739/webspace/httpdocs/req/test_out.class";


//REVISIT:  is addslashes appropriate here ?
$test_title = $_GET['test_title'];

$test_hr = $_GET['test_hr'];
$test_min = $_GET['test_min'];
$test_sec = $_GET['test_sec'];

$usr_id = $_SESSION['usr_id'] ;

//create new instance of the class


function is_int_val($data) 
	{
	if (is_int($data) === true) return true;
	elseif (is_string($data) === true && is_numeric($data) === true) 
	{	
		return (strpos($data, '.') === false);	
	}
	return false;
	}


function checkTestData($out_usr_id, $out_test_title, $out_test_hr, $out_test_min, $out_test_sec)
{
	$result;
	$my = new class_test_out;
	$my->sql_connect();

	
	
		
	if ($out_test_title!="")
	{
			$my->sql_query = "select test_id from tbl_tests where usr_id=$out_usr_id and test_title='$out_test_title'";
			$my->sql_execute();
				if ($my->sql_err)
					{
						return -1;
					}
			
			$num_rows  = $my->sql_num_rows($my->sql_res);
			
					if ($num_rows > 0)
						{
							$result="1";
						}
						else
							$result="0";
						
	}
	else
		$result="1";		
	
	
	if (is_int_val($out_test_hr)==false || is_int_val($out_test_min)==false || is_int_val($out_test_sec)==false)		
			$result.="1";
		else
			$result.="0";
	$my->sql_close();		
	
	return $result;			
}
	$res = checkTestData($usr_id, $test_title, $test_hr, $test_min, $test_sec);

	echo $res;

?>