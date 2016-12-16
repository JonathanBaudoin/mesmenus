<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 17/11/16
 * Time: 13:31
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Ingredient;
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
     * @Route("{page}/", defaults={"page" = 1}, requirements={"page": "\d+"})
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
     * @param string  $slug
     *
     * @return array
     *
     * @Route("ajouter/", name="app_recipe_add")
     * @Route("{slug}/modifier/", name="app_recipe_edit")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/recipe/add.html.twig")
     */
    public function addAction(Request $request, $slug = null)
    {
        if (!is_null($slug)) {
            $recipe = $this->getDoctrine()->getRepository('AppBundle:Recipe')->findOneBy(['slug' => $slug]);
            if (!$recipe) {
                throw $this->createNotFoundException('Cette recette n\'existe pas.');
            }
        } else {
            $recipe = new Recipe();
            $recipe->setUser($this->getUser());
        }

        $em         = $this->getDoctrine()->getManager();
        $recipeForm = $this->createForm(RecipeType::class, $recipe);
        $recipeForm->handleRequest($request);

        if ($recipeForm->isSubmitted()) {
            // Form added ingredients
            $formDataIngredients = $request->request->get('ingredient');

            /** @var Ingredient $ingredient */
            foreach ($recipe->getIngredients() as $ingredient) {
                // Get Recipe's ingredient by recipe and ingredient entities
                $recipeHasIngredient = $this->getDoctrine()->getRepository('AppBundle:RecipeHasIngredients')
                    ->findOneBy([
                        'recipe'     => $recipe,
                        'ingredient' => $ingredient
                    ])
                ;

                // Is no result, new entity
                if (!$recipeHasIngredient) {
                    $recipeHasIngredient = new RecipeHasIngredients();
                }

                $recipeHasIngredient
                    ->setRecipe($recipe)
                    ->setIngredient($ingredient)
                    ->setAmount($formDataIngredients[$ingredient->getId()]['amount'])
                    ->setMeasureUnit($formDataIngredients[$ingredient->getId()]['measureUnit'])
                ;

                // Add RecipeHasIngredients entity and remove Ingredient entity
                $recipe->addIngredient($recipeHasIngredient);
                $recipe->removeIngredient($ingredient);
            }

            if ($recipeForm->isValid()) {
                $em->persist($recipe);
                $em->flush();
                $this->addFlash('notice', $this->get('translator')->trans('recipe.add.success'));
                return $this->redirectToRoute('app_recipe_view', ['slug' => $recipe->getSlug()]);
            }

        }

        return [
            'recipeForm' => $recipeForm->createView(),
        ];
    }

    /**
     * @param Recipe $recipe
     *
     * @return array
     *
     * @Route("{slug}/")
     * @Template("app/recipe/view.html.twig")
     */
    public function viewAction(Recipe $recipe)
    {
        return ['recipe' => $recipe];
    }
}
