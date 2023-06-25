<?php
/**
 * EASYSENDSMS Gateway
 * @url https://www.easysendsms.com
 * @author Titan Systems
 */

define("EASYSENDSMS_GATEWAY", [
	"username" => "andr5tyb2022", // Your username
	"password" => "esm89398", // Your password
	"from" => "Test" // Your sender name
]);

return [
    "send" => function ($phone, $message, &$system) {
        /**
         * Implement sending here
         * @return bool:true
         * @return bool:false
         */

        try {
            $send = $system->guzzle->get("https://www.easysendsms.com/sms/bulksms-api/bulksms-api", [
                "query" => [
                    "username" => EASYSENDSMS_GATEWAY["username"],
                    "password" => EASYSENDSMS_GATEWAY["from"],
                    "from" => EASYSENDSMS_GATEWAY["from"],
                    "to" => $phone,
                    "text" => $message,
                    "type" => 0
                ],
                "allow_redirects" => true,
                "http_errors" => false
            ]);
    
            return true;
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