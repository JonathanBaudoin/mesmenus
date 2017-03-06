<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 18/01/17
 * Time: 16:08
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Menu;
use AppBundle\Entity\ShoppingListIngredients;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ShoppingListRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('sl');
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->findAllQueryBuilder()->getQuery()->getResult();
    }

    /**
     * @param Menu $menu
     *
     * @return QueryBuilder
     */
    public function findByMenuQueryBuilder(Menu $menu)
    {
        return $this->findAllQueryBuilder()
            ->andWhere('sl.menu = :menu')
            ->setParameter('menu', $menu)
        ;
    }

    /**
     * @param Menu $menu
     *
     * @return array
     */
    public function findByMenu(Menu $menu)
    {
        return $this->findByMenuQueryBuilder($menu)->getQuery()->getResult();
    }

    /**
     * @param Menu    $menu
     * @param integer $productId
     *
     * @return null|ShoppingListIngredients
     */
    public function findProductByMenu(Menu $menu, $productId)
    {
        return $this
            ->findByMenuQueryBuilder($menu)
            ->andWhere('sl.id = :productId')
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
