<?php

namespace AOS\Security\Crud\Security\Doctrine;

use AOS\Security\Crud\Permission\Entity\Operation;
use AOS\Security\Crud\Security\SecurityService;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DoctrineSecurityService
{
    protected $logger;
    protected $securityService;

    private $enabled = false;

    public function __construct(Logger $logger, SecurityService $securityService)
    {
        $this->logger = $logger;
        $this->securityService = $securityService;
    }

    public function enable()
    {
        $this->enabled = true;
        $this->logger->debug('Doctrine security enabled');
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function checkCreatePermissions($entity)
    {
        $this->checkPermissions($entity, Operation::CREATE);
    }

    public function checkReadPermissions($entity)
    {
        $this->checkPermissions($entity, Operation::READ);
    }

    public function checkUpdatePermissions($entity)
    {
        $this->checkPermissions($entity, Operation::UPDATE);
    }

    public function checkDeletePermissions($entity)
    {
        $this->checkPermissions($entity, Operation::DELETE);
    }

    private function checkPermissions($entity, $operation)
    {
        if (!$this->enabled) {
            $this->logger->debug('Doctrine security not enabled. Skipping security check.');

            return;
        }

        if (!$this->securityService->hasEntityPermission($entity, $operation)) {
            throw new AccessDeniedException(
                sprintf(
                    'Permission denied because the current user has no permissions to access the entity "%s" with the given context',
                    $entity
                )
            );
        }
    }
}
