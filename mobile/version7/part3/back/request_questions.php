<?php
/* 
 * request_questions.php  by Toni  02/28/2014
 */
	$s_id = $_GET['s_id'];
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
	$result_json = "{\"Questions\": {\"Blocks\": [";
	$query = "select * from Questions where (s_id = '$s_id') AND (q_type < 3)";
	$result = mysql_query($query);
	if($result === FALSE) {
		die(mysql_error()); 
	}
	$info = array();
	while($row = mysql_fetch_assoc($result)) $info[] = $row;
	for ($i=0; $i<count($info); $i++) {
		if ($i>0) $result_json.=',';
		$result_json.='{';
		$result_json.='"q_id":"'.$info[$i]["q_id"].'",';
		$q_id = $info[$i]["q_id"];
		$result_json.='"q_heading":"'.$info[$i]["q_heading"].'",';
		$result_json.='"q_position":"'.$info[$i]["q_position"].'",';
		$result_json.='"q_type":"'.$info[$i]["q_type"].'",';
		$result_json.='"image":"'.$info[$i]["image"].'",';
		$result_json.='"options":[';
		$query_o = "select * from Options where q_id = '$q_id'";
		$result_o = mysql_query($query_o);
		if($result_o === FALSE) {
			die(mysql_error()); 
		}
		$info_o = array();
		while($row_o = mysql_fetch_assoc($result_o)) $info_o[] = $row_o;
		for ($j=0; $j<count($info_o); $j++) {
			if ($i>0) $result_json.=',';
			$result_json.='{';
			$result_json.='"o_id":"'.$info_o[$j]["o_id"].'",';
			$result_json.='"o_text":"'.$info_o[$j]["o_text"].'"}';
		}
		$result_json.=']}';
	}
	$result_json.='],';

	$query = "select * from Questions where (s_id = '$s_id') AND (q_type = 3)";
	$result = mysql_query($query);
	if($result === FALSE) {
		die(mysql_error()); 
	}
	$info = array();
	while($row = mysql_fetch_assoc($result)) $info[] = $row;
	for ($i=0; $i<count($info); $i++) {
		if ($i>0) $result_json.=',';
		$result_json.='{';
		$result_json.='"q_id":"'.$info[$i]["q_id"].'",';
		$q_id = $info[$i]["q_id"];
		$result_json.='"q_heading":"'.$info[$i]["q_heading"].'",';
		$result_json.='"q_position":"'.$info[$i]["q_position"].'",';
		$result_json.='"q_type":"'.$info[$i]["q_type"].'",';
		$result_json.='"image":"'.$info[$i]["image"].'"}';
	}
	$result_json.='],';

	$query = "select * from Questions where (s_id = '$s_id') AND (q_type > 3)";
	$result = mysql_query($query);
	if($result === FALSE) {
		die(mysql_error()); 
	}
	$info = array();
	while($row = mysql_fetch_assoc($result)) $info[] = $row;
	for ($i=0; $i<count($info); $i++) {
		if ($i>0) $result_json.=',';
		$result_json.='{';
		$result_json.='"q_id":"'.$info[$i]["q_id"].'",';
		$q_id = $info[$i]["q_id"];
		$result_json.='"q_heading":"'.$info[$i]["q_heading"].'",';
		$result_json.='"q_position":"'.$info[$i]["q_position"].'",';
		$result_json.='"q_type":"'.$info[$i]["q_type"].'",';
		$result_json.='"image":"'.$info[$i]["image"].'",';
		$result_json.='"options":[';
		$query_o = "select * from Options where q_id = '$q_id'";
		$result_o = mysql_query($query_o);
		if($result_o === FALSE) {
			die(mysql_error()); 
		}
		$info_o = array();
		while($row_o = mysql_fetch_assoc($result_o)) $info_o[] = $row_o;
		for ($j=0; $j<count($info_o); $j++) {
			if ($i>0) $result_json.=',';
			$result_json.='{';
			$result_json.='"o_id":"'.$info_o[$j]["o_id"].'",';
			$result_json.='"o_text":"'.$info_o[$j]["o_text"].'"}';
		}
		$result_json.=']}';
	}
	$result_json.=']}}';
	echo $result_json;
?>