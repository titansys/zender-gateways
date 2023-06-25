<?php
/**
 * Orange SMS Gateway
 * @url https://developer.orange.com
 * @author Titan Systems
 */

define("ORANGE_GATEWAY", [
	"authorization" => "aSsJ6UtbdUnGgN67cV8qkGK262iBy75KkbZhVUbBY0etTCP8kF==", // Your orange authorization header, do not include the word "Basic"
	"senderAddress" => "9620000", // Your sender address, do not add "+" in the beginning of the string
	"senderName" => "OrangeGateway" // Your sender name
]);

return [
    "send" => function ($phone, $message, &$system) {
        /**
         * Implement sending here
         * @return bool:true
         * @return bool:false
         */

		 try {
			$getToken = json_decode($system->guzzle->post("https://api.orange.com/oauth/v3/token", [
				"headers" => [
					"Authorization" => "Basic " . ORANGE_GATEWAY["authorization"]
				],
				"form_params" => [
					"grant_type" => "client_credentials"
				],
				"allow_redirects" => true,
				"http_errors" => false
			])->getBody()->getContents(), true);
	
			if(isset($getToken["token_type"]) && $getToken["token_type"] == "Bearer"):
				$send = $system->guzzle->post("https://api.orange.com/smsmessaging/v1/outbound/tel%3A%2B" . ORANGE_GATEWAY["senderAddress"] . "/requests", [
					"headers" => [
						"Authorization" => "Bearer {$getToken["access_token"]}"
					],
					"json" => [
						"outboundSMSMessageRequest" => [
							"address" => "tel:{$phone}",
							"senderAddress" => "tel:+" . ORANGE_GATEWAY["senderAddress"],
							"senderName" => ORANGE_GATEWAY["senderName"],
							"outboundSMSTextMessage" => [
								"message" => $message
							]
						]
					],
					"allow_redirects" => true,
					"http_errors" => false
				]);
	
				return $send->getStatusCode() == 201 ? true : false;
			else:
				return false;
			endif;
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