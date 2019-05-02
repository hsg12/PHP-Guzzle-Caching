<?php

require_once 'app/guzzle.php';
require_once 'app/interfaces/CacheInterface.php';
require_once 'app/classes/Cache.php';
require_once 'app/classes/CacheService.php';

function handleUncaughtException($e) {
    echo $e->getMessage();
}
set_exception_handler('handleUncaughtException');

$c = new CacheService(new Cache());

// If data from API exists
if ($json['result']) {

    $jsonIterator = new RecursiveIteratorIterator( 
        new RecursiveArrayIterator($json['result']), 
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($jsonIterator as $key => $val) {
        if(is_array($val)) {

          if (null === $c->get($key)) {
            $c->set($key, $val, 300);
          }
        } 

        // in order to check
        echo "<pre>";
        print_r($c->get($key));
        echo "</pre>";
    }

}
