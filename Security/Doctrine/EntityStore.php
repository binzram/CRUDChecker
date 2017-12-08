<?php

namespace AOS\Security\Crud\Security\Doctrine;

use Symfony\Component\Cache\Adapter\PhpFilesAdapter;

class EntityStore
{
    private $cacheName;
    private $cacheItemName;

    protected $cache;

    private function openCache()
    {
        $this->cache = new PhpFilesAdapter($this->cacheName);
    }

    public function store(array $entities)
    {
        $this->openCache();

        $cacheEntities = $this->cache->getItem($this->cacheItemName);
        $cacheEntities->set($entities);

        $this->cache->save($cacheEntities);
    }

    public function setCacheName($cacheName)
    {
        $this->cacheName = $cacheName;
    }

    public function setCacheItemName($cacheItemName)
    {
        $this->cacheItemName = $cacheItemName;
    }
}
