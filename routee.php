<?php
/**
 * Routee SMS Gateway
 * @url https://www.routee.net
 * @author shubra2641 <https://github.com/shubra2641>
 */

define("ROUTEE_GATEWAY", [
	"authorization" => "775sb3ee-f02c-43bc-a500-e96e52f09073", // Your routee authorization token
	"fromId" => "Zender" // The sender ID you want to use
]);

function gatewaySend($phone, $message, &$system)
{
	/**
	 * Implement sending here
	 * @return bool:true
	 * @return bool:false
	 */

	$send = $system->guzzle->post("https://connect.routee.net/sms", [
		"headers" => [
			"Authorization" => "Bearer " . ROUTEE_GATEWAY["authorization"],
			"Content-Type" => "application/json"
		],
		"json" => [
        	"body" => $message,
        	"to" => $phone,
        	"from" => ROUTEE_GATEWAY["fromId"]
        ],
        "allow_redirects" => true,
        "http_errors" => false
	]);

	return $send->getStatusCode() == 200 ? true : false;
}
