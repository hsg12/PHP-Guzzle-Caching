<?php

class APIClass
{
    private $http;
    private $cache;
    private $cacheTime = 300;

    public function __construct(GuzzleHttp\Client $http, CacheInterface $cacheImplementation)
    {
        $this->http = $http;
        $this->cache = $cacheImplementation;
    }

    public function getAPIData($method, $uri, $credentials, $data)
    {
        if (empty($method) || empty($uri) || empty($credentials) || empty($data)) {
            throw new Exception('Wrong argumnets passed to the method');
        }

        $credentials = base64_encode($credentials);

        $data['headers'] = [
            'Authorization' => 'Basic ' . $credentials,
            'Content-type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
        ];

        $response = $this->http->request($method, $uri, $data);

        if ($response) {
            $body = $response->getBody();
            $json = json_decode( $body->getContents(), true )['result'];

            if ($json) {
                $this->cacheData($json);
                return $json;
            }  
        } else {
            return false;
        }
    }

    private function cacheData($data) 
    {
        $jsonIterator = new RecursiveIteratorIterator( 
            new RecursiveArrayIterator($data), 
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($jsonIterator as $key => $val) {
            if(is_array($val)) {

              if (null === $this->cache->get($key)) {
                $this->cache->set($key, $val, $this->cacheTime);
              }
            } 
        }
    }

    public function setCacheTime($time)
    {
        if ($time) {
            $this->cacheTime = $time;
        }
    }
}
