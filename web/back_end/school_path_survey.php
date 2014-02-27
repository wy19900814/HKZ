<?php
/* 
 * school_path_survey.php  by Toni  02/27/2014
 */
	function school_add($sch_name, $sch_address) {
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
		$query = "select count(*) from Schools";
		$result = mysql_query($query);
		if($result === FALSE) {
    		die(mysql_error()); 
		}
		$info = mysql_fetch_array($result);
		$sch_id = $info[0] + 1;
		$query = "insert into Schools(sch_id, sch_name, sch_address) values('$sch_id', '$sch_name', '$sch_address')";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
	}
	function school_modify($sch_id, $sch_name, $sch_address) {
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
		$query = "update Schools set sch_name = '$sch_name', sch_address = '$sch_address' where sch_id = '$sch_id'";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
	}
	function school_delete($sch_id) {
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
		$query = "delete from Associates where p_id in (select p_id from Paths where sch_id = '$sch_id')";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
		$query = "delete from Paths where sch_id = '$sch_id'";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
		$query = "delete from Schools where sch_id = '$sch_id'";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
	}
	function path_add($p_name, $s_longtitude, $s_latitude, $e_longtitude, $e_latitude, $num_block, $sch_id) {
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
		$query = "select count(*) from Paths";
		$result = mysql_query($query);
		if($result === FALSE) {
    		die(mysql_error()); 
		}
		$info = mysql_fetch_array($result);
		$p_id = $info[0] + 1;
		$query = "insert into Paths(p_id, p_name, s_longtitude, s_latitude, e_longtitude, e_latitude, num_block, sch_id) values('$p_id', '$p_name', '$s_longtitude', '$s_latitude', '$e_longtitude', '$e_latitude', '$num_block', '$sch_id')";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
	}
    function path_modify($p_id, $p_name) {
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
		$query = "update Paths set p_name = '$p_name' where p_id = '$p_id'";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
    }
	function path_delete($p_id) {
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
		$query = "delete from Associates where p_id = '$p_id'";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
		$query = "delete from Paths where p_id = '$p_id'";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
	}
	function association_add($p_id, $s_id) {
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
		$query = "insert into Associates (p_id, s_id) values('$p_id', '$s_id')";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
	}
 	function association_delete($p_id, $s_id) {
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
		$query = "delete from Associates where (p_id = '$p_id') AND (s_id = '$s_id')";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
 	}
	function survey_deploy($s_id) {
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
		$query = "update Surveys set deployed = 1 where s_id = '$s_id'";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
	}
    function survey_retract($s_id) {
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
		$query = "update Surveys set deployed = 0 where s_id = '$s_id'";
		$result = mysql_query($query);
		if($result === FALSE) {
			die(mysql_error()); 
		}
    }
	function get_SPS() {
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
		$result_json = '{"Schools":[';
		$query_sch = "select * from Schools";
		$result_sch = mysql_query($query_sch);
		if($result_sch === FALSE) {
			die(mysql_error()); 
		}
		$info_sch = array();
		while($row_sch = mysql_fetch_assoc($result_sch)) $info_sch[] = $row_sch;
		for($i = 0; $i < count($info_sch); $i++) {
			if ($i>0) $result_json.=',';
			$result_json.='{';
			$result_json.='"sch_id":"'.$info_sch[$i]["sch_id"].'",';
			$sch_id = $info_sch[$i]["sch_id"];
			$result_json.='"sch_name":"'.$info_sch[$i]["sch_name"].'",';
			$result_json.='"sch_address":"'.$info_sch[$i]["sch_address"].'",';
			$result_json.='"Paths":[';
			$query_p = "select * from Paths where sch_id = '$sch_id'";
			$result_p = mysql_query($query_p);
			if($result_p === FALSE) {
				die(mysql_error()); 
			}
			$info_p = array();
			while($row_p = mysql_fetch_assoc($result_p)) $info_p[] = $row_p;
			for($j = 0; $j < count($info_p); $j++) {
				if ($j>0) $result_json.=',';
				$result_json.='{';
				$result_json.='"p_id":"'.$info_p[$j]["p_id"].'",';
				$p_id = $info_p[$j]["p_id"];
				$result_json.='"p_name":"'.$info_p[$j]["p_name"].'",';
				$result_json.='"s_longtitude":"'.$info_p[$j]["s_longtitude"].'",';
				$result_json.='"s_latitude":"'.$info_p[$j]["s_latitude"].'",';
				$result_json.='"e_longtitude":"'.$info_p[$j]["e_longtitude"].'",';
				$result_json.='"e_latitude":"'.$info_p[$j]["e_latitude"].'",';
				$result_json.='"num_block":"'.$info_p[$j]["num_block"].'",';
				$result_json.='"Surveys":[';
				$query_s = "select s.s_id, s_name from Surveys s, Associates a where (a.s_id = s.s_id) AND (p_id = '$p_id') ";
				$result_s = mysql_query($query_s);
				if($result_s === FALSE) {
					die(mysql_error()); 
				}
				$info_s = array();
				while($row_s = mysql_fetch_assoc($result_s)) $info_s[] = $row_s;
				for($k = 0; $k < count($info_s); $k++) {
					if ($k>0) $result_json.=',';
					$result_json.='{';
					$result_json.='"s_id":"'.$info_s[$k]["s_id"].'",';
					$result_json.='"s_name":"'.$info_s[$k]["s_name"].'"}';
				}
				$result_json.=']}';
			}
			$result_json.=']}';
		}
		$result_json.=']}';
		return $result_json;
	}
	school_add("USC","3670 Trousdale Pkwy");
	//school_modify("1","Usc","90089");
	//school_delete("1");
	path_add("Jefferson", "1234", "4321", "121412", "213123", "6", "1");
	//path_modify("0","JeffersonBlvd");
	//path_delete("0");
	association_add("1", "23123");
	//association_delete("2", "2");
	//survey_deploy("23123");
	//survey_retract("23123");
	//get_SPS();
	//path_delete("2");
	//school_delete("1");
?>