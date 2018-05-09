<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 25/11/16
 * Time: 13:20
 */

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BaseManager
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    protected $repository;

    /** @var string */
    protected $class;

    public function __construct(EntityManagerInterface $em, $class)
    {
        $this->em         = $em;
        $this->repository = $em->getRepository($class);
        $this->class      = $em->getClassMetadata($class)->name;
    }

    public function save($object)
    {
        if (get_class($object) === $this->class) {
            $this->em->persist($object);
            $this->em->flush();
        }
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

}
