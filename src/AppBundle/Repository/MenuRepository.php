<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 02/11/16
 * Time: 13:38
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Menu;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class MenuRepository
 * @package AppBundle\Repository
 *
 * @author Jonathan Baudoin <jonathan@ddf.agency>
 */
class MenuRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('m')->orderBy('m.dateStart', 'DESC');
    }

    /**
     * @param User $user
     *
     * @return QueryBuilder
     */
    public function findByUserQueryBuilder(User $user): QueryBuilder
    {
        return $this->findAllQueryBuilder()
            ->andWhere('m.user = :user')
            ->setParameter('user', $user)
        ;
    }
}
