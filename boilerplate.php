<?php
/**
 * Your Custom SMS Gateway
 * @author Your Name
 */

function gatewaySend($phone, $message, &$system)
{
	/**
	 * Implement sending here
	 * @return bool:true
	 * @return bool:false
	 */
}

function gatewayCallback($request, &$system)
{
	/**
	 * Implement status callback here if gateway supports it
	 * @return array:MessageID
	 * @return array:Empty
	 */
}