<?php

namespace AOS\Security\Crud\Security\Doctrine;

use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityStoreCleaner implements CacheClearerInterface
{
    protected $cache;

    public function __construct(ContainerInterface $container)
    {
        $cacheName = $container->getParameter('cache.name');

        $this->cache = new PhpFilesAdapter($cacheName);
    }

    public function clear($cacheDir)
    {
        $this->cache->clear();
    }
}
