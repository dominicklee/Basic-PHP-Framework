<?php

$ip = $_SERVER['REMOTE_ADDR']; // your ip address here
$apiKey = "";	//update this with your IP-API key.
$query = @unserialize(file_get_contents('http://pro.ip-api.com/php/'.$ip.'?key='. $apiKey));
$timezn = "na";
$countryCode = "";

if($query && $query['status'] == 'success')
{
	$timezn = $query['timezone'];
	$countryCode = $query['countryCode'];
	
}
if ($timezn != "na")
	date_default_timezone_set($timezn);	//Set timezone
