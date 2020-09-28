<?php

$challenge = $_REQUEST['hub_challenge'];
$verify_token = $_REQUEST['hub_verify_token'];

if ($verify_token === '2188475931415722|ZvgyzohLT8fCI6WWvE2NdpW2EoY') {
	echo $challenge;
}

$input = file_get_contents('php://input');

//https://stackoverflow.com/a/43473016
if ( hash_equals( 'sha1=' . hash_hmac( 'sha1', $input, 'fba0815a57f6ee4a299df2438b21f4c6' ) , $_SERVER['HTTP_X_HUB_SIGNATURE'] ) ) {
	$input = json_decode($input, true);
	// may need to loop through changes array
    $ad_id = $input['entry'][0]['changes'][0]['value']['ad_id'];	
	$form_id = $input['entry'][0]['changes'][0]['value']['form_id'];
	$leadgen_id = $input['entry'][0]['changes'][0]['value']['leadgen_id'];
	$page_id = $input['entry'][0]['changes'][0]['value']['page_id'];
	$adgroup_id = $input['entry'][0]['changes'][0]['value']['adgroup_id'];
	
	// test leadgen_id 373850850038185
	// old user token : EAAcMFb39ZB8oBAKT0bki60aduadJPxLKOOSn1ZAic2nVHAXp3pbYxPsqc9pixqSeJts5E8UrOo7yTJbZCLHCr55QKejzUEZA0w9i1E6r1OulebFtnp1gBUEZCKizIEuQZCJNjpuQ0utQf6hpad2XSARakiMhMNr3ZBJCvohVOvisgZDZD
	// use access_token & me/accounts edge to
	// get page access tokens that never expire
	
	//TODO: Error handling
	$result = file_get_contents('https://graph.facebook.com/v3.2/' . $leadgen_id . '?access_token=EAAfGaEJ91KoBANKAA86JLHuQtoZCgImMQAWQ3JAISfjh0FVAsIxYZCMjzjfXALBlw0o7valvk431ZC5WsAmSuxAzJuKuCUXLLigf8vbqx3JEag0OcjaKuHFM2NhP6GKUzWxz5ZBrFW8g7ofCZBPsB7F0gT76G7T4ZD');
	
	$leadData = json_decode($result, true);
	
    $lead = $fullName = array();
	$sourceId = $listCode = $emailAddress = $firstName = $lastName = '';
		
	for ($i = 0; $i < count($leadData['field_data']); $i++) {
		$field_data = $leadData['field_data'];
		//format data
		if ($field_data[$i]['name'] === 'sourceId') {
			$sourceId = $field_data[$i]['values'][0];
		}
		
		if ($field_data[$i]['name'] === 'listCode') {
			$listCode = $field_data[$i]['values'][0];
		}		

		if ($field_data[$i]['name'] === 'email' || $field_data[$i]['name'] === 'what_is_your_preferred_email_address?') {
			$emailAddress = $field_data[$i]['values'][0];
		}

		if ($field_data[$i]['name'] === 'full_name') {
			$fullName = explode(" ", $field_data[$i]['values'][0]);
		}

		if ($field_data[$i]['name'] === 'first_name') {
			$firstName = $field_data[$i]['values'][0];
		}

		if ($field_data[$i]['name'] === 'last_name') {
			$lastName = $field_data[$i]['values'][0];
		}
	}

	//create payload
	$lead_array = array(
		'signup' => array(
			'listCode' => 'SIWARMIP',//$listCode,
			'emailAddress' => $emailAddress,
			'sourceId' => $sourceId ? $sourceId : 'XMISSING',
			'referenceNumber' => $leadData['id'],
			'firstName' => !empty($firstName) ? $firstName : $fullName[0],
			'lastName' => !empty($lastName) ? $lastName : ( !empty($fullName) ? count($fullName) === 2 ? $fullName[1] : $fullName[2] : '' ),
			'middleInitial' => !empty($fullName) ? count($fullName) === 2 ? '' : substr($fullName[1], 0, 1) : ''
		)
	);

    $lead = json_encode($lead_array);
	
	//Send to SUA2: https://signupapp2.com/signupapp
	//Initiate cURL.
	$ch = curl_init('https://signupapp2.com/signupapp/signups/process');

	//Tell cURL that we want to send a POST request.
	curl_setopt($ch, CURLOPT_POST, 1);

	//Attach our encoded JSON string to the POST fields.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $lead);

	//Set the content type to application/json
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-auth-token: ab39ccf2-4463-4697-9c0f-f813c22ffb9f')); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

	//Execute the request
	$result = curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);
	
	$result = json_decode($result, true);
	
	if( !empty($result) ) {  
		$result['data'] = $lead_array;
		
		file_put_contents('./error_log_' . date("j.n.Y") . '.txt', json_encode($result), FILE_APPEND);
	}
}