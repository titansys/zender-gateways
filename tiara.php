<?php
/**
 * Tiara SMS Platform  // Tiara is an sms gateway Platform used by many sms providers, its a robust sms gateway systsem for handling complex sms routing
 https://meliora.co.ke/product/tiara
 * @author MELIORA TECHNOLOGIES
 */

/**
* What is Tiara?
* Tiara SMS Gateway is a fast, simple, and secure enterprise SMS Gateway that simplifies how enterprises communicate to their customers via SMS.
* It supports various messaging protocols and simplifies SMS integrations. 
* Multiple applications can connect to the gateway and the gateway can reliably route SMS traffic to multiple mobile network operators.
 
 */

define("TIARA_GATEWAY", [
	"siteUrl" => "http://.....address/api/messaging/sendsms", // Meliora test SMS site url, don't add ending slash
	"apiToken" => "", // Meliora  test SMS client api token.
	"senderId" => "" // The sender ID of the message.
]);

function gatewaySend($phone, $message, &$system)
{
	/**
	 * Implement sending here
	 * @return bool:true
	 * @return bool:false
	 */

	$send = json_decode($system->guzzle->post(TIARA_GATEWAY["siteUrl"], [
		"headers" => [
			"Authorization" => "Bearer " . TIARA_GATEWAY["apiToken"]
		],
		"json" => [
        	"to" => str_replace("+", "", $phone),
        	"from" => TIARA_GATEWAY["senderId"],
        	"message" => $message
        ],
        "allow_redirects" => true,
        "http_errors" => false
	])->getBody()->getContents(), true);

	try {
		return isset($send["status"]) && strtolower($send["status"]) == "success" ? true : false;
	} catch(Exception $e){
		return false;
	}
}
