<?php
/* 
 * survey_import.php
 * using auth_client.php by Manuel Lemos
 */
function get_SMList() {
	require('http.php');
	require('oauth_client.php');

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
		for($i=0;$i<count($array);$i++)
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
function survey_import(s_id) {

}
function survey_delete(s_id) {

}

