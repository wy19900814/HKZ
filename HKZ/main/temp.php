<?php
    header('Content-Type: application/json;charset=utf-8');
	include'survey_import.php';
	$arr=get_SMList();
		echo $arr;
?>