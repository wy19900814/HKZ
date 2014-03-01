<?php
/* 
 * receive_answers.php  by Toni  03/01/2014
 */
	$data = json_decode(file_get_contents("php://input"), true);
	$address="68.178.143.53";
	$username="uschkz";
	$password="Team14!hkz";
	$database="uschkz";
	$connection=mysql_connect ($address, $username, $password);
	if (!$connection) {
	  die('Not connected : ' . mysql_error());
	}
	
	$db_selected = mysql_select_db($database, $connection);
	if (!$db_selected) {
	  die ('Can\'t use db : ' . mysql_error());
	}
	$info = $data['Answers'];
	for ($i=0; $i<count($info); $i++) {
		$q_id = $info[$i]['q_id'];
		$block_id = $info[$i]['block_id'];
		$p_id = $info[$i]['p_id'];
		$a_content = $info[$i]['a_content'];
		$query = "select count(*) from Answers";
		$result = mysql_query($query);
		if($result === FALSE) {
    		die(mysql_error()); 
		}
		$info_a = mysql_fetch_array($result);
		$a_id = $info_a[0] + 1;
		$query_a = "insert into Answers(a_id, a_content, q_id, p_id, block_id) values ($a_id, $a_content, $q_id, $p_id, $block_id)";
		$result = mysql_query($query_a);
		if($result === FALSE) {
    		die(mysql_error()); 
		}
	}
?>