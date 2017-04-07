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

/**
 * Class IngredientRepository
 * @package AppBundle\Repository
 *
 * @author Jonathan Baudoin <jonathan@ddf.agency>
 */
class IngredientRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('i')->orderBy('i.name', 'ASC');
    }
}