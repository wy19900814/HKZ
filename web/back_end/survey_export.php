<?php
/* 
 *  survey_export.php  by Toni  03/25/2014
 */
	function survey_export($s_id) {	
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

		$query_q = "select q_id from Questions where s_id = '$s_id'";
		$result_q = mysql_query($query_q);
		if($result_q === FALSE) {
			die(mysql_error()); 
		}
		$info_q = array();
		while($row_q = mysql_fetch_assoc($result_q)) $info_q[] = $row_q;

		for ($j=0; $j<count($info_q); $j++) {
			$q_id = $info_q[$j]['q_id'];
			echo $q_id.",";
		}
		echo "<br/>";
		$query_p = "select p_id from Associates where s_id = '$s_id'";
		$result_p = mysql_query($query_p);
		if($result_p === FALSE) {
			die(mysql_error()); 
		}
		$info_p = array();
		while($row_p = mysql_fetch_assoc($result_p)) $info_p[] = $row_p;

		for ($i=0; $i<count($info_p); $i++) {
			$p_id = $info_p[$i]['p_id'];
			$query_latlong = "select s_latitude, s_longtitude from Paths where p_id = '$p_id'";
			$result_latlong = mysql_query($query_latlong);
			if($result_latlong === FALSE) {
	    		die(mysql_error()); 
			}
			$info_latlong = mysql_fetch_array($result_latlong);
			echo $info_latlong[0].','.$info_latlong[1];
			for ($j=0; $j<count($info_q); $j++) {
				$q_id = $info_q[$j]['q_id'];
				$query_a = "select a_content from Answers where (p_id = '$p_id') AND (q_id = '$q_id')";
				$result_a = mysql_query($query_a);
				if($result_a === FALSE) {
		    		die(mysql_error()); 
				}
				$info_a = mysql_fetch_array($result_a);
				echo ",";
				if ($info_a[0])
					echo $info_a[0];
				else echo "-9";
			}
			echo ",<br/>";
		}
	}
	survey_export("46973666");
?>