<?php
/**
 * Twilio SMS Gateway
 * @author Titan Systems
 */

function gatewaySend($phone, $message, &$system)
{
	/**
	 * Implement sending here
	 * @return bool:true
	 * @return bool:false
	 */

	$fromPhone = ""; // Twilio number to use 
	$accountSid = ""; // Your twilio Account SID
	$authToken = ""; // Your twilio authentication token
	$callbackId = ""; // Callback ID you got from zender admin panel

	$send = json_decode($system->guzzle->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
		"form_params" => [
        	"From" => $fromPhone,
        	"Body" => $message,
        	"To" => $phone,
        	"StatusCallback" => site_url("gateway/{$callbackId}", true)
        ],
        "auth" => [
        	$accountSid,
        	$authToken
        ],
        "allow_redirects" => true,
        "http_errors" => false
	])->getBody()->getContents());

	if($send->status == "queued"):
		return $send->sid;
	else:
		return false;
	endif;
}

function gatewayCallback($request, &$system)
{
	$callbackId = ""; // Callback ID you got from zender admin panel

	$system->cache->container("system.gateways");

	if($system->cache->has("{$callbackId}.{$request["MessageSid"]}")):
		if($request["MessageStatus"] == "sent"):
			return $system->cache->get("{$callbackId}.{$request["MessageSid"]}");
		else:
			return false;
		endif;
	else:
		return false;
	endif;
}