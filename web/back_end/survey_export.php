<?php
/* 
 *  survey_export.php  by Toni  03/25/2014
 */
	function survey_export($s_id) {	
		//header("Content-Type: text/plain");
		header('Content-type: application/octet-stream');
		header('Content-Disposition: attachment; filename="filename.csv"');
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

		$query_q = "select q_position,q_type from Questions where s_id = '$s_id'";
		$result_q = mysql_query($query_q);
		if($result_q === FALSE) {
			die(mysql_error()); 
		}
		$info_q = array();
		while($row_q = mysql_fetch_assoc($result_q)) $info_q[] = $row_q;
		echo "latitude,longtitude,";
		for ($k=1; $k < 7; $k++) { 
			for ($j=0; $j<count($info_q); $j++) 
				if ($info_q[$j]['q_type'] < 3) {
				$q_pos = $info_q[$j]['q_position'];
				echo "B".$k."_Q".$q_pos.",";
			}
		}
		for ($j=0; $j<count($info_q); $j++) 
			if ($info_q[$j]['q_type'] == 3) {
			$q_pos = $info_q[$j]['q_position'];
			echo "TQ".$q_pos.",";
		}
		for ($j=0; $j<count($info_q); $j++) 
			if ($info_q[$j]['q_type'] > 3) {
			$q_pos = $info_q[$j]['q_position'];
			echo "OQ".$q_pos.",";
		}
		echo "\n";
		$query_p = "select p_id from Associates where s_id = '$s_id'";
		$result_p = mysql_query($query_p);
		if($result_p === FALSE) {
			die(mysql_error()); 
		}
		$info_p = array();
		while($row_p = mysql_fetch_assoc($result_p)) $info_p[] = $row_p;

		for ($i=0; $i<count($info_p); $i++) {

			$p_id = $info_p[$i]['p_id'];
			$query_rec = "select distinct rec_id from Answers A, Questions Q where (p_id = '$p_id') AND (A.q_id = Q.q_id) AND (Q.s_id = '$s_id')";
			$result_rec = mysql_query($query_rec);
			if($result_rec === FALSE) {
	    		die(mysql_error()); 
			}
			$info_rec = array();
			while($row_rec = mysql_fetch_assoc($result_rec)) $info_rec[] = $row_rec;
			if ($info_rec === FALSE) continue;

			for ($r=0; $r<count($info_rec); $r++) {

				$rec_id = $info_rec[$r]['rec_id'];
				$query_latlong = "select s_latitude, s_longtitude from Paths where p_id = '$p_id'";
				$result_latlong = mysql_query($query_latlong);
				if($result_latlong === FALSE) {
		    		die(mysql_error()); 
				}
				$info_latlong = mysql_fetch_array($result_latlong);
				echo $info_latlong[0].','.$info_latlong[1];
				for ($k=1; $k < 7; $k++) { 
					for ($j=0; $j<count($info_q); $j++) 
						if ($info_q[$j]['q_type'] < 3) {
							$q_pos = $info_q[$j]['q_position'];
							$query_a = "select a_content from Answers A, Questions Q where (p_id = '$p_id') AND (q_position = '$q_pos') AND (block_id = '$k') AND (Q.q_id = A.q_id) AND (rec_id = '$rec_id') AND (Q.s_id = '$s_id') AND (q_type < 3)";
							$result_a = mysql_query($query_a);
							if($result_a === FALSE) {
					    		die(mysql_error()); 
							}
							$info_a = mysql_fetch_array($result_a);
							echo ",";
							if ($info_a[0])
								echo $info_a[0];
							else echo "-1";
						}
				}
				for ($j=0; $j<count($info_q); $j++) 
					if ($info_q[$j]['q_type'] == 3) {
						$q_pos = $info_q[$j]['q_position'];
						$query_a = "select a_content from Answers A, Questions Q where (p_id = '$p_id') AND (q_position = '$q_pos') AND (Q.q_id = A.q_id) AND (rec_id = '$rec_id') AND (Q.s_id = '$s_id') AND (q_type = 3)";
						$result_a = mysql_query($query_a);
						if($result_a === FALSE) {
				    		die(mysql_error()); 
						}
						$info_a = mysql_fetch_array($result_a);
						echo ",";
						echo $info_a[0];
				}
				for ($j=0; $j<count($info_q); $j++) 
					if ($info_q[$j]['q_type'] > 3) {
						$q_pos = $info_q[$j]['q_position'];
						$query_a = "select a_content from Answers A, Questions Q where (p_id = '$p_id') AND (q_position = '$q_pos') AND (Q.q_id = A.q_id) AND (rec_id = '$rec_id') AND (Q.s_id = '$s_id') AND (q_type > 3)";
						$result_a = mysql_query($query_a);
						if($result_a === FALSE) {
				    		die(mysql_error()); 
						}
						$info_a = mysql_fetch_array($result_a);
						echo ",";
						if ($info_a[0])
							echo $info_a[0];
						else echo "-1";
				}		
				echo ",\n";
			}
		}
	}
	survey_export($_GET['s_id']);
?>