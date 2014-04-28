<?
//Description: test deletion
//THIS IS AJAX FILE
//Directory: /ajax/GoTest/CurQuesiton/index.php
//
//Created: by Dd on Feb 02, 2009
//
//Last Edit: by Dd on Feb 02, 2010
//
session_start();

	require_once "/usr/local/pem/vhosts/107739/webspace/httpdocs/req/test_out.class";

//MUST CHECK TEST ID HERE: integer? exists?
	$test_id = $_GET['test_id'];
	$question_id = $_GET['question_id'];
	
	//create new instance of the class
	$my = new class_test_out;
	$my->sql_connect();
	
	if ($_GET['direction']=='finish')
	{	//FINISH TEST
	
	echo "finish";
	
	}
	else
	{	//GENERATE AND OUTPUT NEXT QUESTION
		if ($_GET['direction']=='next'){$more_less_sign = ">"; $sort = "ASC"; $and = "AND question_id$more_less_sign'$question_id'"; $limit = "0,1";}
		elseif ($_GET['direction']=='prev'){$more_less_sign = "<";$sort = "DESC"; $and = "AND question_id$more_less_sign'$question_id'"; $limit = "0,1";}
		else {$more_less_sign = ""; $sort = "ASC"; $and = ""; $limit = ($_GET['direction']-1).",1";}
		
		//Get new question
		$sql_question = $my->sql_query ("SELECT question_id FROM tbl_test_questions WHERE test_id='$test_id' $and ORDER BY question_id $sort LIMIT $limit");
		$my->sql_error(); if ($my->sql_err) echo '"ERROR #73: mySQL error msg: '.$my->sql_err.' <br />" ';
	
		// If there are qusetions for test
		if ($my->sql_num_rows($sql_question)>0)
		{
			list($cur_question_id) = $my->sql_fetch_row($sql_question);
			echo $cur_question_id;
		}
		
	}

?>
