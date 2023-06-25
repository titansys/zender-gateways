<?php
/**
 * ClickSend SMS Gateway
 * @author ReantoAscencio
 */

define("CLICKSEND_GATEWAY", [
	"apiUser" => "", // Clicksend apu User
	"apiKey" => "", // Clicksend api key
    "senderId" => "", // Clicksend sender id
    "source" => "php"
]);

return [
    "send" => function ($phone, $message, &$system) {
        /**
         * Implement sending here
         * @return bool:true
         * @return bool:false
         */

		$send = $system->guzzle->post("https://rest.clicksend.com/v3/sms/send", [
			"headers" => [
				"Authorization" => "Basic " . CLICKSEND_GATEWAY["apiKey"]
			],
            "json" => [
				"messages" => [
                    [
                        "source" => CLICKSEND_GATEWAY["source"],
                        "from" => CLICKSEND_GATEWAY["senderId"],
                        "body" => $message,
                        "to" => $phone
                    ]
                ]
			],
			"allow_redirects" => true,
			"http_errors" => false
		]);
	
		if($send->getStatusCode() == 200):
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