<?php
/**
 * ClickSend SMS Gateway
 * @author Renato
 * @url https://sms.mxedia.com
 */


function gatewaySend($phone, $message, &$system)
{
    $apiUrl = 'https://rest.clicksend.com/v3/sms/send';
    $apiUser = 'YOUR_API_USER'; // Replace with your api User
    $apiKey = 'YOUR_API_KEY'; // Replace with your api Key
    $senderId = 'SENDER_ID'; // Replace with your sender ID
    $source = 'php';
    
    $postData = array(
        'messages' => array(
            array(
                'source' => $source,
                'from' => $senderId,
                'body' => $message,
                'to' => $phone
            )
        )
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($apiUser . ':' . $apiKey)
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        // Message sent successfully
        return true;
    } else {
        // Error sending message
        $system = $response;
        return false;
    }
}
