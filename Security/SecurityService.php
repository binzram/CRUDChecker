<?php

namespace AOS\Security\Crud\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;

class SecurityService
{
    private $cacheItemName;

    private $cache;
    private $tokenStorage;

    public function __construct(string $cacheName, string $cacheItemName, TokenStorageInterface $tokenStorage)
    {
        $this->cacheItemName = $cacheItemName;

        $this->cache = new PhpFilesAdapter($cacheName);
        $this->tokenStorage = $tokenStorage;
    }

    public function getUserRoles()
    {
        // Test
        return ['ROLE1'];

        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            // For the dev toolbar
            return null;
        }

        if ($token instanceof TokenInterface) {
            return $token->getRoles();
        }

        return [];
    }

    public function getPermissions(UserInterface $user = null)
    {
        if (null === $user) {
            $userRoles = $this->getUserRoles();
        } else {
            $userRoles = $user->getRoles();
        }

        $entities = $this->cache->getItem($this->cacheItemName)->get();
        $entities = array_filter($entities, function ($entity) use ($userRoles) {
            foreach ($entity['roles'] as $role) {
                if (in_array($role, $userRoles)) {
                    return true;
                }
            }

            return false;
        });

        return $entities;
    }

    public function hasEntityPermission($entity, $operation)
    {
        $permissions = $this->getPermissions();

        foreach ($permissions as $permissionEntity => $permission) {
            if ($permissionEntity == $entity) {
                if (in_array($operation, $permission['permissions'])) {
                    return true;
                }
            }
        }

        return false;
    }
}
