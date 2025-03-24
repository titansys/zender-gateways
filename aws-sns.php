<?php
/**
 * AWS SNS SMS Gateway via REST API with Sender ID
 * @author Mocit Limited
 */

define("AWS_SNS_GATEWAY", [
    "region"    => "",         // AWS region (e.g., 'us-east-1')
    "accessKey" => "",         // Your AWS Access Key
    "secretKey" => "",         // Your AWS Secret Key
    "senderId"  => ""          // Your SMS Sender ID (up to 11 alphanumeric characters)
]);

return [
    "send" => function ($phone, $message, &$system) {
        /**
         * Send an SMS via AWS SNS using a REST HTTP request.
         * @param string $phone   The recipient's phone number (in E.164 format)
         * @param string $message The SMS message to send
         * @param object $system  System object providing a Guzzle HTTP client (e.g., $system->guzzle)
         * @return bool True if the message is successfully sent, false otherwise.
         */
        try {
            $region    = AWS_SNS_GATEWAY['region'];
            $accessKey = AWS_SNS_GATEWAY['accessKey'];
            $secretKey = AWS_SNS_GATEWAY['secretKey'];
            $senderId  = AWS_SNS_GATEWAY['senderId'];
            $service   = 'sns';
            $host      = "sns.$region.amazonaws.com";
            $endpoint  = "https://$host/";

            // Prepare the POST parameters (form-encoded)
            $params = [
                "Action"    => "Publish",
                "PhoneNumber" => $phone,
                "Message"     => $message,
                "Version"     => "2010-03-31",
                // Set the sender ID in SMS attributes
                "MessageAttributes.entry.1.Name"                => "AWS.SNS.SMS.SenderID",
                "MessageAttributes.entry.1.Value.DataType"        => "String",
                "MessageAttributes.entry.1.Value.StringValue"     => $senderId
            ];
            
            // Build the request body
            $body = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
            $payloadHash = hash('sha256', $body);

            // Dates for headers and credential scope
            $amz_date   = gmdate('Ymd\THis\Z'); // e.g., 20250324T123600Z
            $date_stamp = gmdate('Ymd');         // e.g., 20250324

            // Prepare canonical request components
            $canonical_uri         = '/';
            $canonical_query_string = ""; // POST request with form-encoded body
            $headers = [
                "Content-Type" => "application/x-www-form-urlencoded; charset=utf-8",
                "Host"         => $host,
                "X-Amz-Date"   => $amz_date
            ];
            
            // Build canonical headers string and signed headers list
            $canonical_headers = "";
            $signed_headers_arr = [];
            foreach ($headers as $key => $value) {
                $lowerKey = strtolower($key);
                $canonical_headers .= $lowerKey . ":" . trim($value) . "\n";
                $signed_headers_arr[] = $lowerKey;
            }
            sort($signed_headers_arr);
            $signed_headers = implode(";", $signed_headers_arr);
            
            // Create canonical request string
            $canonical_request = "POST\n" .
                $canonical_uri . "\n" .
                $canonical_query_string . "\n" .
                $canonical_headers . "\n" .
                $signed_headers . "\n" .
                $payloadHash;
            
            // Prepare string to sign
            $algorithm = "AWS4-HMAC-SHA256";
            $credential_scope = $date_stamp . "/" . $region . "/" . $service . "/aws4_request";
            $string_to_sign = $algorithm . "\n" .
                $amz_date . "\n" .
                $credential_scope . "\n" .
                hash('sha256', $canonical_request);
            
            // Calculate the signing key
            $kSecret  = "AWS4" . $secretKey;
            $kDate    = hash_hmac('sha256', $date_stamp, $kSecret, true);
            $kRegion  = hash_hmac('sha256', $region, $kDate, true);
            $kService = hash_hmac('sha256', $service, $kRegion, true);
            $kSigning = hash_hmac('sha256', "aws4_request", $kService, true);
            
            // Compute the signature
            $signature = hash_hmac('sha256', $string_to_sign, $kSigning);
            
            // Build the Authorization header
            $authorization_header = $algorithm . " " .
                "Credential=" . $accessKey . "/" . $credential_scope . ", " .
                "SignedHeaders=" . $signed_headers . ", " .
                "Signature=" . $signature;
            
            // Add the Authorization header
            $headers["Authorization"] = $authorization_header;
            
            // Send the request using the system's Guzzle HTTP client
            $response = $system->guzzle->post($endpoint, [
                "headers"         => $headers,
                "body"            => $body,
                "allow_redirects" => true,
                "http_errors"     => false
            ]);
            
            // Get and parse the XML response
            $responseBody = (string)$response->getBody();
            $xml = simplexml_load_string($responseBody);
            if ($xml === false) {
                return false;
            }
            // Check if a MessageId exists in the response to confirm success
            if (isset($xml->PublishResult->MessageId) && !empty((string)$xml->PublishResult->MessageId)) {
                return true;
            }
        } catch (Exception $e) {
            // Optionally log errors using $system (if available)
            // e.g., $system->logger->error($e->getMessage());
        }
        return false;
    },
    "callback" => function ($request, &$system) {
        /**
         * AWS SNS does not natively support SMS status callbacks via REST.
         * This function is provided for interface compatibility.
         * @return array MessageID if applicable, or empty array.
         */
    }
];
