<?php
/* Unit test for receive_answers.php
 * receive_answers_unit_test.php  by Toni  03/01/2014
 */
	$ans1 = new stdClass;
	$ans1->a_id = '12313';
	$ans1->a_content = '12';
	$ans1->q_id = '1';
	$ans1->p_id = '1';
	$ans1->block_id = '1';
	$data = new stdClass;
	$data->Answers = Array('0' => $ans1);

	$url = "http://letsallgetcovered.org/lets6502/hkztest2/receive_answers.php";    
	$content = json_encode($data);
	echo $content;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

	$json_response = curl_exec($curl);

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	echo "Status $status, response $json_response";

	curl_close($curl);
?>