<?php
/**
 * Twilio SMS Gateway
 * @author Titan Systems
 */

function gatewaySend($phone, $message, &$system){
	/**
	 * Implement sending here
	 * @return true:Success
	 * @return false:Failed
	 */

	$fromPhone = ""; // Twilio number to use 
	$accountSid = ""; // Your twilio Account SID
	$authToken = ""; // Your twilio authentication token

	$send = json_decode($system->guzzle->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
		"form_params" => [
        	"From" => $fromPhone,
        	"Body" => $message,
        	"To" => $phone
        ],
        "auth" => [
        	$accountSid,
        	$authToken
        ],
        "allow_redirects" => true,
        "http_errors" => false
	])->getBody()->getContents());

	sleep(3);

	if($send->status == "queued"):
		$verify = json_decode($system->guzzle->get("https://api.twilio.com/{$send->uri}", [
	        "auth" => [
	        	$accountSid,
	        	$authToken
	        ],
	        "allow_redirects" => true,
	        "http_errors" => false
		])->getBody()->getContents());

		if($verify->status == "delivered"):
			return true;
		else:
			return false;
		endif;
	else:
		return false;
	endif;
}