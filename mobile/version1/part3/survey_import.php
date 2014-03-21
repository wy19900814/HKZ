<?php
/* 
 * survey_import.php     by Toni  02/27/2014
 * using auth_client.php by Manuel Lemos
 */
require('http.php');
require('oauth_client.php');


function get_SMList() {

	$client = new oauth_client_class;
	$client->debug = false;
	$client->debug_http = true;
	$client->server = 'SurveyMonkey';
	//$client->redirect_uri = 'http://10.120.14.248/mobile/testimport.php';
	$client->redirect_uri = 'http://letsallgetcovered.org/lets6502/hkz/mobile/testimport.php';//redirect page

	$client->client_id = 'tnt_7'; $application_line = __LINE__; //__LINE__为常量。当前文件的行数  __FILE__为文件名（含路径）,也是常量
	$client->client_secret = 'vU6qCdAVfD52eRPn6GTrcJCTxYK63zaJ';
	$client->api_key = 'g29chz7j2tms2b5rdvmhqacq';
	$client->scope = '';

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				$parameters = array('fields' => array("title", "date_created", "date_modified"),'page_size' => 1000, 'page' => 1);
				$success = $client->CallAPI(  //CallAPI($url, $method, $parameters, $options, &$response)
					'https://api.surveymonkey.net/v2/surveys/get_survey_list?api_key='.$client->api_key,
					'POST', $parameters, array('FailOnAccessError'=>true, 'RequestContentType'=>'application/json'), $surveys);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
		//echo HtmlSpecialChars(print_r($surveys->data->surveys));
		$result = '{"SMSurvey":[';
		$array = $surveys->data->surveys;
		for($i = 0; $i < count($array); $i++)
		{
			if ($i>0) $result.=',';
			$result.='{';
			$result.='"s_id":"'.$array[$i]->survey_id.'",';
			$result.='"s_name":"'.htmlspecialchars($array[$i]->title).'",';
			$result.='"date_created":"'.$array[$i]->date_created.'",';
			$result.='"date_modified":"'.$array[$i]->date_modified.'"}';
		}
		$result.=']}';
		return $result;
	}
	else
	{
		echo HtmlSpecialChars($client->error);
	}
}
function survey_import($s_id) {

	$client = new oauth_client_class;
	$client->debug = false;
	$client->debug_http = true;
	$client->server = 'SurveyMonkey';
	$client->redirect_uri = 'http://letsallgetcovered.org/lets6502/hkztest2/survey_import.php';

	$client->client_id = 'tnt_7'; $application_line = __LINE__; 
	$client->client_secret = 'vU6qCdAVfD52eRPn6GTrcJCTxYK63zaJ';
	$client->api_key = 'g29chz7j2tms2b5rdvmhqacq';
	$client->scope = '';

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				$parameters = array('survey_id' => $s_id);
				$success = $client->CallAPI(
					'https://api.surveymonkey.net/v2/surveys/get_survey_details?api_key='.$client->api_key,
					'POST', $parameters, array('FailOnAccessError'=>true, 'RequestContentType'=>'application/json'), $surveydetails);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
			//echo '<pre>Survey1 details: ', HtmlSpecialChars(print_r($surveydetails->data)),'</pre>';
			$page_num = count($surveydetails->data->pages);
			if ($page_num>3) {
				return "Error while importing a survey: more than 3 pages";
			} elseif ($page_num<3) {
				return "Error while importing a survey: less than 3 pages";
			} else {
				$blocks  = $surveydetails->data->pages[0];
				$tallies = $surveydetails->data->pages[1];
				$others  = $surveydetails->data->pages[2];
				if ((strtolower($blocks->heading) != "block") || (strtolower($tallies->heading) != "tally") || (strtolower($others->heading) != "other")){
					return "Error while importing a survey: unexpected page heading";
				}
				for($i = 0; $i < count($blocks->questions); $i++) {
					if (($blocks->questions[$i]->type->family != "single_choice") && ($blocks->questions[$i]->type->family != "multiple_choice")) {
						return "Error while importing a survey: unexpected question type";
					}
				}
				for($i = 0; $i < count($tallies->questions); $i++) {
					if (($tallies->questions[$i]->type->family != "presentation") || ($tallies->questions[$i]->type->subtype != "descriptive_text")) {
						return "Error while importing a survey: unexpected question type";
					}
				}
				for($i = 0; $i < count($others->questions); $i++) {
					if (($others->questions[$i]->type->family != "single_choice") && ($others->questions[$i]->type->family != "multiple_choice")) {
						return "Error while importing a survey: unexpected question type";
					}
				}
			}
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
			$s_name = mysql_real_escape_string($surveydetails->data->title->text);
			$date_created  = mysql_real_escape_string($surveydetails->data->date_created);
			$date_modified = mysql_real_escape_string($surveydetails->data->date_modified);
			$num_response  = 0;
			$num_question  = $surveydetails->data->question_count;
			$deployed 	   = 0;
			//insert surveys
			$query = "insert into Surveys(s_id, s_name, date_created, date_modified, num_response, num_question, deployed) values('$s_id', '$s_name', '$date_created', '$date_modified', '$num_response', '$num_question', '$deployed')";
			$result = mysql_query($query);
			if($result === FALSE) {
	    		die(mysql_error()); 
			}
			//insert questions
			for($i = 0; $i < count($blocks->questions); $i++) {
				$query = "select count(*) from Questions";
				$result = mysql_query($query);
				if($result === FALSE) {
		    		die(mysql_error()); 
				}
				$info = mysql_fetch_array($result);
				$q_id = $info[0] + 1;
				$q_heading = $blocks->questions[$i]->heading;
				if ($blocks->questions[$i]->type->family == 'single_choice') $q_type = 1;
				elseif ($blocks->questions[$i]->type->family == 'multiple_choice') $q_type = 2;
				$query = "insert into Questions(q_id, q_heading, q_type, q_position, s_id) values('$q_id', '$q_heading', '$q_type', '$i', '$s_id')";
				$result = mysql_query($query);
				if($result === FALSE) {
		    		die(mysql_error()); 
				}
				//insert options
				$options = $blocks->questions[$i]->answers;
				for($j = 0; $j < count($options); $j++) {
					$o_id = $options[$j]->position;
					$o_text = $options[$j]->text;
					$query = "insert into Options(o_id, q_id, o_text) values('$o_id', '$q_id', '$o_text')";
					$result = mysql_query($query);
					if($result === FALSE) {
			    		die(mysql_error()); 
					}
				}
			}
			for($i = 0; $i < count($tallies->questions); $i++) {
				$query = "select count(*) from Questions";
				$result = mysql_query($query);
				if($result === FALSE) {
		    		die(mysql_error()); 
				}
				$info = mysql_fetch_array($result);
				$q_id = $info[0] + 1;
				$q_heading = $tallies->questions[$i]->heading;
				if (($tallies->questions[$i]->type->family == 'presentation') && ($tallies->questions[$i]->type->subtype == 'descriptive_text')) $q_type = 3;
				$query = "insert into Questions(q_id, q_heading, q_type, q_position, s_id) values('$q_id', '$q_heading', '$q_type', '$i', '$s_id')";
				$result = mysql_query($query);
				if($result === FALSE) {
		    		die(mysql_error()); 
				}
			}
			for($i = 0; $i < count($others->questions); $i++) {
				$query = "select count(*) from Questions";
				$result = mysql_query($query);
				if($result === FALSE) {
		    		die(mysql_error()); 
				}
				$info = mysql_fetch_array($result);
				$q_id = $info[0] + 1;
				$q_heading = $others->questions[$i]->heading;
				if ($others->questions[$i]->type->family == 'single_choice') $q_type = 4;
				elseif ($others->questions[$i]->type->family == 'multiple_choice') $q_type = 5;
				$query = "insert into Questions(q_id, q_heading, q_type, q_position, s_id) values('$q_id', '$q_heading', '$q_type', '$i', '$s_id')";
				$result = mysql_query($query);
				if($result === FALSE) {
		    		die(mysql_error()); 
				}
				$options = $others->questions[$i]->answers;
				for($j = 0; $j < count($options); $j++) {
					$o_id = $options->position;
					$o_text = $options->text;
					$query = "insert into Options(o_id, q_id, o_text) values('$o_id', '$q_id', '$o_text')";
					$result = mysql_query($query);
					if($result === FALSE) {
			    		die(mysql_error()); 
					}
				}
			}
	}
	else
	{
		echo HtmlSpecialChars($client->error);
	}
}
function get_DBList() {
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
	$query = "select * from Surveys";
	$result = mysql_query($query);
	if($result === FALSE) {
		die(mysql_error()); 
	}

	$info = array();
	while($row = mysql_fetch_assoc($result)) $info[] = $row;
	$result_json = '{"DBSurvey":[';
	for($i = 0; $i < count($info); $i++) {
		if ($i>0) $result_json.=',';
		$result_json.='{';
		$result_json.='"s_id":"'.$info[$i]["s_id"].'",';
		$result_json.='"s_name":"'.$info[$i]["s_name"].'",';
		$result_json.='"date_created":"'.$info[$i]["date_created"].'",';
		$result_json.='"date_modified":"'.$info[$i]["date_modified"].'",';
		$result_json.='"deployed":"'.$info[$i]["deployed"].'"}';
	}
	$result_json.=']}';
	return $result_json;
}
function survey_delete($s_id) {
	require('school_path_survey.php');
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
	$query_a = "select p_id from Associates where s_id = '$s_id'";
	$result_a = mysql_query($query_a);
	if($result_a === FALSE) {
		die(mysql_error()); 
	}
	$info_a = array();
	while($row_a = mysql_fetch_assoc($result_a)) $info_a[] = $row_a;
	for ($i=0; $i<count($info_a); $i++) {
		$p_id = $info_a[$i]['p_id'];
		association_delete($p_id, $s_id);
	}

	$query_q = "delete from Options where q_id in (select q_id from Questions where s_id = $s_id)";
	$result_q = mysql_query($query_q);
	if($result_q === FALSE) {
		die(mysql_error()); 
	}
	$query_o = "delete from Questions where s_id = $s_id";
	$result_o = mysql_query($query_o);
	if($result_o === FALSE) {
		die(mysql_error()); 
	}

	$query = "delete from Surveys where s_id = '$s_id'";
	$result = mysql_query($query);
	if($result === FALSE) {
		die(mysql_error()); 
	}
}
//echo get_SMList();
//survey_import("46973666");
//get_DBList();
//survey_delete("123456");
