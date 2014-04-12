<?php
	$filename = date('Y-m-d H:i:s');
	header('Content-type: application/octet-stream');
	header('Content-Disposition: attachment; filename="marker_'.$filename.'.csv"');
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
	$query = "select p_name,m_latitude,m_longtitude,comment from Markers M, Paths P where M.p_id = P.p_id order by M.p_id asc";
	$result = mysql_query($query);
	if($result === FALSE) {
		die(mysql_error()); 
	}
	$info = array();
	while($row = mysql_fetch_assoc($result)) $info[] = $row;
	echo "path_name,marker_latitude,marker_longtitude,comment,";
	for ($i=0; $i < count($info); $i++) { 
		echo $info[$i]['p_name'].','.$info[$i]['m_latitude'].','.$info[$i]['m_longtitude'].','.$info[$i]['comment'].",\n";
	}
?>