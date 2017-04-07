<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 02/11/16
 * Time: 13:38
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class RecipeRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('r')->orderBy('r.name', 'ASC');
    }
}