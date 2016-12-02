<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 17/11/16
 * Time: 13:31
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeHasIngredients;
use AppBundle\Form\RecipeType;
use AppBundle\Repository\RecipeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RecipeController
 * @package AppBundle\Controller
 *
 * @Route("/recettes/")
 */
class RecipeController extends Controller
{

    /**
     * @param Request $request
     * @param int     $page
     *
     * @return array
     *
     * @Route("{page}", defaults={"page" = 1}, requirements={"page": "\d+"})
     * @Template("app/recipe/list.html.twig")
     */
    public function listAction(Request $request, $page = 1)
    {
        /** @var RecipeRepository $recipeRepository */
        $recipeRepository = $this->getDoctrine()->getRepository('AppBundle:Recipe');
        $qb               = $recipeRepository->findAllQueryBuilder();
        $pagination       = $this->get('app.services.paginator')->paginate($qb, $page);

        return [
            'pagination' => $pagination
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @Route("ajouter/")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/recipe/add.html.twig")
     */
    public function addAction(Request $request)
    {
        $recipe = new Recipe();
        $recipe->setUser($this->getUser());
        $recipeForm = $this->createForm(RecipeType::class, $recipe);
        $recipeForm->handleRequest($request);

        if ($recipeForm->isSubmitted()) {

            /*$tmp = new RecipeHasIngredients();
            $recipe->addIngredient($tmp);

            foreach ($recipe->getIngredients() as $ingredient) {
                $recipe->addIngredient($ingredient);
            }*/

            dump($recipeForm->get('ingredients')->getViewData());
            die();
        }

        return [
            'recipeForm' => $recipeForm->createView(),
        ];
    }
}
