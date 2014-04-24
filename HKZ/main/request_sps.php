<?php
    require("request_surveys.php");
	echo $_GET['jsoncallback']."(".get_SPS().")";
?>