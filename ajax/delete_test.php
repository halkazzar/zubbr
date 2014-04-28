<?
//Description: test deletion
//
//Directory: /ajax/delete_test.php
//
//Created: by Dd on Dec 16, 2009
//
//Last Edit: by Mus on Dec 19, 2009
//

if ($_GET['test'])
{
	require_once "/usr/local/pem/vhosts/107739/webspace/httpdocs/req/test_out.class";
	
	$my = new class_test_out;
	$my->sql_connect();
	
	function is_int_val($data) 
	{
	if (is_int($data) === true) return true;
	elseif (is_string($data) === true && is_numeric($data) === true) 
	{	
		return (strpos($data, '.') === false);	
	}
	return false;
	}
	
	//Deletes test by given test ID
	function test_out_delete($test_id) 
	{

	//Mus: test_id check as required...
	if (is_int_val($test_id)==false)
	//if($my->sql_error())
		{

			$my->msg_text = 'ERROR: Deleting test: no appropriate test_id provided <br />';
			return(11);
		}

	//create new instance of the class
	$my = new class_test_out;
	$my->sql_connect();
	
		//Get questions of my test
		$my->sql_query = "SELECT question_id  FROM tbl_questions WHERE test_id='$test_id'";
		$my->sql_execute();
		if($my->sql_err) 
		{

			$my->msg_text = 'ERROR: query=['.$my->sql_query.']<br />';
			$my->msg_text .= 'ERROR: mySQL error msg: '.$my->sql_err.' <br />';
			return(11);
		}
		

		
		// If there are qusetions for my test
		if ($my->sql_num_rows($my->sql_res)>0)
		{
			while (list($question_id) = $my->sql_fetch_row($my->sql_res))
			{

				//Get answers of curent question
				$sql_result = $my->sql_query("SELECT answer_id FROM tbl_answers WHERE question_id='$question_id'");
				if($my->sql_error())
				{
					$my->msg_text = 'ERROR: mySQL error msg: '.$my->sql_err.' <br />';
					return(11);
				}
		
				
				if ($my->sql_num_rows($sql_result)>0)
				{
					
					//Delete all answers
					while (list($answer_id) = $my->sql_fetch_row($sql_result))
					{
						$sql_delete_answer = $my->sql_query("DELETE FROM tbl_answers WHERE question_id='$question_id'");
						if($my->sql_error())
						{
							$my->msg_text = 'ERROR: Deleting answer: mySQL error msg: '.$my->sql_err.' <br />';
							return(11);
						}
					}
				}
				
				//Delete questions
				$sql_delete_question = $my->sql_query("DELETE FROM tbl_questions WHERE question_id='$question_id'");
				if($my->sql_error())
				{
					$my->msg_text = 'ERROR: Deleting qustion: mySQL error msg: '.$my->sql_err.' <br />';
					return(11);
				}
			}
		}
		
		//Delete test

		$sql_delete_question = $my->sql_query("DELETE FROM tbl_tests WHERE test_id='$test_id'");
		if($my->sql_error())
		{
			$my->msg_text = 'ERROR: Deleting test: mySQL error msg: '.$my->sql_err.' <br />';
			return(11);
		}
		
	return 0;
	}
	
//	echo $_GET['test']; 
	 
	$err = test_out_delete($_GET['test']);
	if ($err) 
			{
				$my->msg_pretty_print(1,$my->msg_text);
			}
 
$my->msg_pretty_print(1,$my->msg_text);
 $my->sql_close();
 echo "DELETED";
}



?>