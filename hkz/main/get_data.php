<?php include('school_path_survey.php');
	  include('survey_import.php');
      if(isset($_POST['list'])){
      	$arr=get_SPS();
      	echo $arr;
      };
      if(isset($_POST['list_db'])){
      	$arr=get_DBList();
      	echo $arr;
      }
?>