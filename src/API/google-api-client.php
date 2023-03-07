<?php

require_once __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('C:\Users\mejri\OneDrive\Bureau\BloodBond\BloodBound-1\src\API\code_secret_client_501106910018-pf5bie9b4k3nlafvbllam17btva4vkq5.apps.googleusercontent.com.json');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setRedirectUri('http://localhost:8000/facility/facility/{id}');
