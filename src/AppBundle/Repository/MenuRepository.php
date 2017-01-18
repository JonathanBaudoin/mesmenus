<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 02/11/16
 * Time: 13:38
 */

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository
{
    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('m')->orderBy('m.dateStart', 'DESC');
    }

    public function findByUserQueryBuilder(User $user)
    {
        return $this->findAllQueryBuilder()
            ->andWhere('m.user = :user')
            ->setParameter('user', $user)
        ;
    }
}