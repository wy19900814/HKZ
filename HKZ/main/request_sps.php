<?php
    require("school_path_survey.php");
	echo $_GET['jsoncallback']."(".get_SPS().")";
?>