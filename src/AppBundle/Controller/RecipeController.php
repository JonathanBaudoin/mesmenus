<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 17/11/16
 * Time: 13:31
 */

namespace AppBundle\Controller;

use AppBundle\Repository\RecipeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends Controller
{

    /**
     * @Route("/recettes/{page}", defaults={"page" = 1}, requirements={"page": "\d+"})
     */
    public function listAction(Request $request, $page = 1)
    {
        /** @var RecipeRepository $recipeRepository */
        $recipeRepository = $this->getDoctrine()->getRepository('AppBundle:Recipe');
        $qb = $recipeRepository->findAllQueryBuilder();

        $pagination = $this->get('app.services.paginator')->paginate($qb, $page);

        die(dump($pagination));

        return new Response($pagination);
    }
}