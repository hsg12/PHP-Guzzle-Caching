<?php

use GuzzleHttp\Client;

require 'vendor/autoload.php';

$client = new Client();

// hide API key in repository
$credentials = base64_encode('api:key');

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
  ],
  'headers' => [
      'Authorization' => 'Basic ' . $credentials,
      'Content-type' => 'application/json; charset=utf-8',
      'Accept' => 'application/json',
  ],
];

$response = $client->post('https://api.printful.com/shipping/rates', $data);

$body = $response->getBody();

$json = json_decode( $body->getContents(), true );
