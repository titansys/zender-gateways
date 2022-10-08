<?php
/**
 * Twilio SMS Gateway
 * @author Titan Systems
 */

define("TWILIO_GATEWAY", [
	"accountSid" => "", // Your twilio Account SID
	"authToken" => "", // Your twilio authentication token
	"serviceSid" => "" // Messaging Service Sid
]);

function gatewaySend($phone, $message, &$system)
{
	/**
	 * Implement sending here
	 * @return bool:true
	 * @return bool:false
	 */

	$send = json_decode($system->guzzle->post("https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_GATEWAY["accountSid"] . "/Messages.json", [
		"form_params" => [
        	"MessagingServiceSid" => TWILIO_GATEWAY["serviceSid"],
        	"Body" => $message,
        	"To" => $phone
        ],
        "auth" => [
        	TWILIO_GATEWAY["accountSid"],
        	TWILIO_GATEWAY["authToken"]
        ],
        "allow_redirects" => true,
        "http_errors" => false
	])->getBody()->getContents());

	if($send->status == "accepted"):
		return $send->sid;
	else:
		return false;
	endif;
}