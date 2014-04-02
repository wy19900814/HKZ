<?php
/* 
 * request_surveys.php  by Toni  03/01/2014
 */
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
				$query_s = "select s.s_id, s_name, deployed from Surveys s, Associates a where (a.s_id = s.s_id) AND (p_id = '$p_id') ";
				$result_s = mysql_query($query_s);
				if($result_s === FALSE) {
					die(mysql_error()); 
				}
				$info_s = array();
				while($row_s = mysql_fetch_assoc($result_s)) $info_s[] = $row_s;
				$flag = 0;
				for($k = 0; $k < count($info_s); $k++) {
					if ($info_s[$k]["deployed"] == 1) {
						if ($flag > 0) $result_json.=','; 
						else $flag = 1;
						$result_json.='{';
						$result_json.='"s_id":"'.$info_s[$k]["s_id"].'",';
						$result_json.='"s_name":"'.$info_s[$k]["s_name"].'"}';
					}
				}
				$result_json.=']}';
			}
			$result_json.=']}';
		}
		$result_json.=']}';
		return $result_json;
	}
	echo get_SPS();
?>