<?php 
	$q_id = $_GET['q_id'];
	$url  = $_GET['url'];
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
	$query = "update Questions set url = '$url' where q_id = '$q_id'";
	$result = mysql_query($query);
	if($result === FALSE) {
		die(mysql_error()); 
	}
?>