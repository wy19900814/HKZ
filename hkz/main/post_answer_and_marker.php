<?php
/* 
 * post_answers.php  by Toni  03/01/2014
 */
	header("Access-Control-Allow-Origin: *");
	$json_answer_and_marker_str=$_POST['json_answer_and_marker_str'];
	$data=json_decode(stripslashes($json_answer_and_marker_str),true);
	//echo $json_answer_and_marker_str;
	$address="68.178.143.53";
	$username="uschkz";
	$password="Team14!hkz";
	$database="uschkz";
	$success = true;
	$connection=mysql_connect ($address, $username, $password);
	if (!$connection) {
	  die('Not connected : ' . mysql_error());
	  $success = false;
	}
	
	$db_selected = mysql_select_db($database, $connection);
	if (!$db_selected) {
	  die ('Can\'t use db : ' . mysql_error());
	  $success = false;
	}
	$answers = $data['Content']['Answers'];
	$markers= $data['Content']['Markers'];
	$answers_query = "select count(*) from Answers group by p_id,q_id,block_id";
	$answers_result = mysql_query($answers_query);
	if($answers_result === FALSE) {
    	die(mysql_error()); 
    	$success = false;
	}

	$answers_info = mysql_fetch_array($answers_result);
	$rec_id = $answers_info[0] + 1;
	//print_r($rec_id);
	//echo $answers[0]['a_content'];
	//echo $markers[0]['m_longtitude'];
	for ($i=0; $i<count($answers); $i++) {
		$q_id = $answers[$i]['q_id'];
		$block_id = $answers[$i]['block_id'];
		$p_id = $answers[$i]['p_id'];
		$a_content = $answers[$i]['a_content'];

		$query = "select max(a_id) from Answers";
		$result = mysql_query($query);

		if($result === FALSE) {
    		die(mysql_error()); 
    		$success = false;
		}
		$info_a = mysql_fetch_array($result);
		$a_id = $info_a[0] + 1;

		$query_a = "insert into Answers(a_id, a_content, q_id, p_id, block_id, rec_id) values ($a_id, '$a_content', $q_id, $p_id, $block_id, $rec_id)";
		$result_a = mysql_query($query_a);
		if($result_a === FALSE) {
    		die(mysql_error()); 
    		$success = false;
		}

	}
	for ($i=0; $i<count($markers); $i++) {
		$s_id = $markers[$i]['s_id'];
		$p_id = $markers[$i]['p_id'];
		$m_longtitude = $markers[$i]['m_longtitude'];
		$m_latitude = $markers[$i]['m_latitude'];
		$comment = $markers[$i]['comment'];

		$query = "select max(m_id) from Markers";
		$result = mysql_query($query);

		if($result === FALSE) {
    		die(mysql_error()); 
    		$success = false;
		}
		$info_a = mysql_fetch_array($result);
		$m_id = $info_a[0] + 1;

		$query_a = "insert into Markers(m_id, s_id, p_id, m_longtitude, m_latitude,comment) values ($m_id, $s_id, $p_id, $m_longtitude, $m_latitude, '$comment')";
		$result_a = mysql_query($query_a);
		if($result_a === FALSE) {
    		die(mysql_error()); 
    		$success = false;
		}
		
	}
	if ($success) echo "success";
	else echo "fail";
?>
