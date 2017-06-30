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
use Doctrine\ORM\QueryBuilder;

class RecipeRepository extends EntityRepository
{
    /**
     * @param User|null $user
     * @param bool      $public
     *
     * @return QueryBuilder
     */
    public function findAllQueryBuilder(User $user = null, $public = true): QueryBuilder
    {
        $qb = $this
            ->createQueryBuilder('r')
            ->andWhere('r.public = :public')
            ->setParameter('public', $public)
            ->orderBy('r.name', 'ASC')
        ;

        if (!is_null($user)) {
            $qb
                ->orWhere('r.user = :user')
                ->setParameter('user', $user)
            ;
        }

        return $qb;
    }
}