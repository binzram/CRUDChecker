<?php

namespace AOS\Security\Crud\Security\Doctrine;

class EntityStore
{
    protected $entities = [];

    public function store(array $entities): void
    {
        $this->entities = $entities;
    }
}
