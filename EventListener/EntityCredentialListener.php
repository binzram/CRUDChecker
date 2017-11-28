<?php

namespace AOS\Security\Crud\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\onFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityCredentialListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    private $doctrineSecurityService = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function getSecurityService()
    {
        if (null === $this->doctrineSecurityService) {
            $this->doctrineSecurityService = $this->container->get('aos.crud.security.doctrine.doctrineSecurityService');
        }

        return $this->doctrineSecurityService;
    }

    /**
     * catch the postLoad event - after a successfull select statement.
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->getSecurityService()->checkReadPermissions($args->getEntity());
    }

    /**
     * catch the onFlush event - after a persist / flush call
     * to insert, update or delete some data with doctrine.
     *
     * @param onFlushEventArgs $args
     */
    public function onFlush(onFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->getSecurityService()->checkCreatePermissions($entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->getSecurityService()->checkUpdatePermissions($entity);
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $this->getSecurityService()->checkDeletePermissions($entity);
        }

        $uow->computeChangeSets();
    }
}
