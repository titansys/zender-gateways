<?php
/**
 * Ultimate SMS Gateway
 * @author Titan Systems
 */

/**
 * Multiple Gateway Support
 * If you want to use different gateway sending servers of Ultimate SMS, you will need to create an account for 
 * each gateway then create a new api token. Upload different controllers with each api tokens to zender for 
 * each sending servers.
 */

define("USMS_GATEWAY", [
	"siteUrl" => "https://your-site-url.com", // Ultimate SMS site url, don't add ending slash
	"apiToken" => "", // Ultimate SMS client api token. read: https://bit.ly/3dXO2qP
	"senderId" => "" // The sender ID of the message. read: https://bit.ly/3dXO2qP
]);

function gatewaySend($phone, $message, &$system)
{
	/**
	 * Implement sending here
	 * @return bool:true
	 * @return bool:false
	 */

	$send = json_decode($system->guzzle->post(USMS_GATEWAY["siteUrl"] . "/api/v3/sms/send", [
		"headers" => [
			"Authorization" => "Bearer " . USMS_GATEWAY["apiToken"]
		],
		"json" => [
        	"recipient" => str_replace("+", "", $phone),
        	"sender_id" => USMS_GATEWAY["senderId"],
        	"type" => "plain",
        	"message" => $message
        ],
        "allow_redirects" => true,
        "http_errors" => false
	])->getBody()->getContents(), true);

	try {
		return isset($send["status"]) && $send["status"] == "success" ? true : false;
	} catch(Exception $e){
		return false;
	}
}