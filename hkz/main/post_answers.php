<?php
/* 
 * post_answers.php  by Toni  03/01/2014
 */
	header("Access-Control-Allow-Origin: *");
	$name=$_POST['json_answer_str'];
	$data=json_decode(stripslashes($name),true);
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
	$info = $data['Answers'];
	$query_r = "select count(*) from Answers group by p_id,q_id,block_id";
	$result_r = mysql_query($query_r);
	if($result_r === FALSE) {
    	die(mysql_error()); 
    	$success = false;
	}

	$info_r = mysql_fetch_array($result_r);
	$rec_id = $info_r[0] + 1;
	for ($i=0; $i<count($info); $i++) {
		$q_id = $info[$i]['q_id'];
		$block_id = $info[$i]['block_id'];
		$p_id = $info[$i]['p_id'];
		$a_content = $info[$i]['a_content'];

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
	if ($success) echo "success";
	else echo "fail";
?>
