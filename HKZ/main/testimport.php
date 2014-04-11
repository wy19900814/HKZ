<?php
	require("sps.php");
	require("survey_import.php");
	//school_add("university of southern california","2003 Zonal Ave. Los Angeles, CA 90089");
	//school_modify("2","university of southern california","2003 Zonal Ave. Los Angeles, CA 90089");
	//school_delete("4");
	//function path_add($p_name, $s_longtitude, $s_latitude, $e_longtitude, $e_latitude, $num_block, $sch_id)
	//path_add("jefferson/figuora","34.3","-123.3","35.3","-67.5","2","5");
	$result=get_SMList();
?>
<html>
<head>
</head>
<body>
<?php
   echo $result;

?>
</body>

</html>
	