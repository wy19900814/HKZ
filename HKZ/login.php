<?php
	$nm=$_POST["name"];
	$ps=$_POST["pass"];
/* 
 * login.php  by Toni  02/26/2014
 */
	function login($u_name, $pwd) {

		$address="68.178.143.53";
		$username="uschkz";
		$password="Team14!hkz";
		$database="uschkz";
		$connection=mysql_connect ($address, $username, $password);
		if (!$connection) {
		  die('Not connected : ' . mysql_error());
		}
		// Set the active MySQL database
		$db_selected = mysql_select_db($database, $connection);
		if (!$db_selected) {
		  die ('Can\'t use db : ' . mysql_error());
		}

		// Insert new row with user data
		$hash_pwd = mysql_real_escape_string(md5($pwd));
		$query = "select count(u_id) from Users where (u_name = \"$u_name\") and (pwd = \"$hash_pwd\")";
		$result = mysql_query($query);
		if($result === FALSE) {
    		die(mysql_error()); // TODO: better error handling
		}
		$info=mysql_fetch_array($result);
		if ($info[0] == 1) {
			return true;
		}elseif ($info[0] == 0) {
			return false;
		}
	}
	if(login($nm,$ps)){$result=true;}
	else{$result=false;}
	echo json_encode(array('success' => $result, ));
?>