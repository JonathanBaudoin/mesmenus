<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 17/11/16
 * Time: 13:43
 */

namespace AppBundle\Services;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    /** @var PaginatorInterface */
    protected $paginator;

    /** @var RequestStack */
    protected $request;

    /** @var int */
    protected $pageRange;

    public function __construct(PaginatorInterface $paginator, RequestStack $requestStack, $pageRange)
    {
        $this->paginator = $paginator;
        $this->request   = $requestStack;
        $this->pageRange = $pageRange;
    }

    public function paginate(QueryBuilder $qb, $page, $pageRange = null)
    {
        if (is_null($pageRange)) {
            $pageRange = $this->pageRange;
        }

        return $this->paginator->paginate(
            $qb,
            $this->request->getCurrentRequest()->query->getInt('page', $page),
            $pageRange
        );
    }
}