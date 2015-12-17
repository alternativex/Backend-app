<?php


class MemcachedService {

    private $memcached;

    private function __construct()
    {
        $this->memcached = new Memcached();
        $this->memcached->addServer(Config::get("cache.memcached.0.host"), Config::get("cache.memcached.0.port"));
    }

    public static function instance()
    {
        return new MemcachedService();
    }

    public function set($key, $var, $expire = 0)
    {
        $this->memcached->set($key, $var, $expire);
    }

    public function get($key)
    {
        $this->memcached->get($key);
    }

    function has($key)
    {
        $res = $this->memcached->get($key);
        return Memcached::RES_SUCCESS === $this->memcached->getResultCode();
    }

    public function delete($key)
    {
        $this->memcached->delete($key);
    }
}