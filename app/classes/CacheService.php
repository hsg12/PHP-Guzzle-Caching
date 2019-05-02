<?php

class CacheService
{
  private $cache;

  public function __construct(CacheInterface $cacheImplementation) 
  {
    $this->cache = $cacheImplementation;
  }

  public function set(string $key, $value, int $duration)
  {
    $this->cache->set($key, $value, $duration);
  }

  public function get(string $key)
  {
    return $this->cache->get($key);
  }
}
