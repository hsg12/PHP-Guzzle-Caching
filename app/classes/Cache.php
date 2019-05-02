<?php

class Cache implements CacheInterface
{
  private $_cachepath = 'app/cache/';
  private $_cachename = 'data';
  private $_extension = '.json';

  public function set(string $key, $value, int $duration)
  {
    $storeData = array(
      'time'   => time(),
      'expire' => $duration,
      'data'   => $value
    );
    $dataArray = $this->_loadCache();
    
    if (true === is_array($dataArray)) {
      $dataArray[$key] = $storeData;
    } else {
      $dataArray = array($key => $storeData);
    }
    $cacheData = json_encode($dataArray, JSON_PRETTY_PRINT);
    
    file_put_contents($this->getCacheDir(), $cacheData);
    
    return $this;
  }
  
  public function get(string $key) 
  {
    $cachedData = $this->_loadCache();

    if (!isset($cachedData[$key]['data'], $cachedData[$key]['time'], $cachedData[$key]['expire'])) {
      return null;
    }

    $exp = $cachedData[$key]['time'] + $cachedData[$key]['expire'];

    if (time() > $exp) {
      return null;
    } else {
      return $cachedData[$key]; // to check cached with 'expire'
      // return $cachedData[$key]['data']; // data output
    }
  }
  
  private function _loadCache() 
  {
    if (true === file_exists($this->getCacheDir())) {
      $file = file_get_contents($this->getCacheDir());
      return json_decode($file, true);
    } else {
      return false;
    }
  }
  
  public function getCacheDir() 
  {
    if (true === $this->_checkCacheDir()) {
      $filename = $this->getCache();
      return $this->getCachePath() . $filename . $this->_extension;
    }
  }
  
  private function _checkCacheDir() 
  {
    if (!is_dir($this->getCachePath()) && !mkdir($this->getCachePath(), 0775, true)) {
      throw new Exception('Unable to create cache directory ' . $this->getCachePath());
    } elseif (!is_readable($this->getCachePath()) || !is_writable($this->getCachePath())) {
      if (!chmod($this->getCachePath(), 0775)) {
        throw new Exception($this->getCachePath() . ' must be readable and writeable');
      }
    }

    return true;
  }

  public function getCachePath() 
  {
    return $this->_cachepath;
  }
 
  public function getCache() 
  {
    return $this->_cachename;
  }
  
}
