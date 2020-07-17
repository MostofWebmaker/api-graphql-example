<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

class Flusher extends EntityManager
{
    private EntityManagerInterface $em;

    /**
     * Flusher constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
       // parent::__construct($conn,  $config,  $eventManager);
    }

    /**
     * @param object $object
     */
    public function persist($object): void
    {
        $this->em->persist($object);
    }

    public function flush($entity = NULL): void
    {
        $this->em->flush();
    }

    public function beginTransaction(): void
    {
    	$this->em->beginTransaction();
    }

    public function commit(): void
    {
    	$this->em->commit();
    }

    public function rollback(): void
    {
    	$this->em->rollback();
    }
    /**
     * @param object $object
     */
    public function remove($object): void
    {
        $this->em->remove($object);
    }
}
