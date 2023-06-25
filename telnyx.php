<?php
/**
 * Telnyx SMS Gateway
 * @author Titan Systems
 */

define("TELNYX_GATEWAY", [
	"apikey" => "KEY018423367F8AHJS71A7A74EDB6C20F25_of4tdzcVXFptkszhkjdsd7", // Your telnyx api key
	"from" => "+13642220854", // Your telnyx phone number
]);

return [
    "send" => function ($phone, $message, &$system) {
        /**
         * Implement sending here
         * @return bool:true
         * @return bool:false
         */

		try {
			$send = $system->guzzle->post("https://api.telnyx.com/v2/messages", [
				"headers" => [
					"Accept" => "application/json",
					"Content-Type" => "application/json",
					"Authorization" => "Bearer " . TELNYX_GATEWAY["apikey"]
				],
				"json" => [
					"from" => TELNYX_GATEWAY["from"],
					"to" => $phone,
					"text" => $message
				],
				"allow_redirects" => true,
				"http_errors" => false
			]);
	
			return $send->getStatusCode() == 200 ? true : false;
		} catch(Exception $e){
			return false;
		}
    },
    "callback" => function ($request, &$system) {
        /**
         * Implement status callback here if gateway supports it
         * @return array:MessageID
         * @return array:Empty
         */

    }
];