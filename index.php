<?php

use GuzzleHttp\Client;

require 'vendor/autoload.php';

require_once 'app/interfaces/CacheInterface.php';
require_once 'app/classes/APIClass.php';
require_once 'app/classes/Cache.php';


// Handling exceptions
function handleUncaughtException($e) {
    echo $e->getMessage();
}
set_exception_handler('handleUncaughtException');


$service = new APIClass(new Client(), new Cache());
// $service->setCacheTime('20');

$method = 'POST';
$uri = 'https://api.printful.com/shipping/rates';
$credentials = '77qn9aax-qrrm-idki:lnh0-fm2nhmp0yca7';
$data = [
  'json' => [
    "recipient" => [
        "address1" => "11025 Westlake Dr",
        "city" => "Charlotte",
        "country_code" => "US",
        "state_code" => "NC",
        "zip" => 28273
    ],
    "items" => [
        [
            "quantity" => 2,
            "variant_id" => 7679
        ]
    ]
  ]
];

$response = $service->getAPIData($method, $uri, $credentials, $data);

// to check
echo '<pre>';
print_r($response);
echo '<pre>';
