<?php
/* 
 * survey_import.php
 * using auth_client.php by Manuel Lemos
 */
require('http.php');
require('oauth_client.php');


function get_SMList() {

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
				$parameters = array('fields' => array("title", "date_created", "date_modified"),'page_size' => 1000, 'page' => 1);
				$success = $client->CallAPI(
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
			$result.='"s_name":"'.$array[$i]->title.'",';
			$result.='"date_created":"'.$array[$i]->date_created.'",';
			$result.='"date_modified":"'.$array[$i]->date_modified.'"}';
		}
		$result.=']}';
		echo $result;
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
			echo '<pre>Survey1 details: ', HtmlSpecialChars(print_r($surveydetails->data)),'</pre>';
			$page_num = count($surveydetails->data->pages);
			if ($page_num>3) {
				echo "Error while importing a survey: more than 3 pages";
			} elseif ($page_num<3) {
				echo "Error while importing a survey: less than 3 pages";
			} else {
				$blocks  = $surveydetails->data->pages[0];
				$tallies = $surveydetails->data->pages[1];
				$others  = $surveydetails->data->pages[2];
				for($i = 0; $i < count($blocks); $i++) {
					if ((($blocks[$i]->type->subtype != "single_choice") && ($blocks[$i]->type->subtype != "multiple_choice") && ($blocks[$i]->type->subtype != "presentation"))  
						||  (($blocks[$i]->type->subtype == "presentation") && ($blocks[$i]->type->family != "image"))) {
						echo "Error while importing a survey: unexpected question type";
					}
				}
				
			}
	}
	else
	{
		echo HtmlSpecialChars($client->error);
	}
}
function survey_delete($s_id) {

}
//get_SMList();
survey_import("46973666");
