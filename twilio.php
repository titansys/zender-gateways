<?php
/**
 * Twilio SMS Gateway
 * @author Titan Systems
 */

define("TWILIO_GATEWAY", [
	"accountSid" => "", // Your twilio Account SID
	"authToken" => "" // Your twilio authentication token
]);

return [
    "send" => function ($phone, $message, &$system) {
        /**
         * Implement sending here
         * @return bool:true
         * @return bool:false
         */

		$send = json_decode($system->guzzle->post("https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_GATEWAY["accountSid"] . "/Messages.json", [
			"form_params" => [
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
			return true;
		else:
			return false;
		endif;
    },
    "callback" => function ($request, &$system) {
        /**
         * Implement status callback here if gateway supports it
         * @return array:MessageID
         * @return array:Empty
         */

    }
];