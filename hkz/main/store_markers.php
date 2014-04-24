<?php
	header("Access-Control-Allow-Origin: *");
	$flag=true;
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
	foreach ($_POST as $param_name => $param_val) {
		if ($param_name == 'title') $title = $param_val;
		if ($param_name == 'position') {
			$pos = substr($param_val, 1);
			$lat = strstr($pos, ',', true);
			$long = substr(strstr($pos, ','), 1, -1);
		}
		if ($param_name == 'survey_id') $s_id = $param_val;
                if ($param_name == 'comment') $comment = $param_val;
                if ($param_name == 'path_id') $p_id = $param_val;
	}
	//$data = $title . $lat . $long . $comment;
	//file_put_contents("/Users/Tony/Desktop/result.txt", $data);
	$query = "select max(m_id) from Markers";
	$result = mysql_query($query);
	if($result === FALSE) {
    	die(mysql_error()); 
    	$flag=false;
	}
	$info_m = mysql_fetch_array($result);
	$m_id = $info_m[0] + 1;
	//$s_id = '123';
	//$p_id = '123';
	$query_m = "insert into Markers(m_id, m_longtitude, m_latitude, comment, s_id, p_id) values ($m_id, $long, $lat, '$comment', $s_id, $p_id)";
	$result = mysql_query($query_m);
	if($result === FALSE) {
    	die(mysql_error()); 
    	$flag=false;
	}
	if($flag){
		echo "success";
	}else{
		echo "failed";
	}

?>