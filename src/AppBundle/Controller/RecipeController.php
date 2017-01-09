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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class RecipeController
 * @package AppBundle\Controller
 *
 * @Route("/recettes/")
 */
class RecipeController extends Controller
{

    /**
     * @param int $page
     *
     * @return array
     *
     * @Route("{page}/", defaults={"page" = 1}, requirements={"page": "\d+"})
     * @Template("app/recipe/list.html.twig")
     */
    public function listAction($page = 1)
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
     * @param Request     $request
     * @param null|string $slug
     *
     * @return array|RedirectResponse
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
            $recipeIngredients   = [];

            /** @var RecipeHasIngredients $recipeIngredient */
            foreach ($recipe->getIngredients() as $recipeIngredient) {
                // Create an array with recipe ingredients from form
                $recipeIngredients[$recipeIngredient->getIngredient()->getId()] = $recipeIngredient;

                // If ingredient has been removed
                if (!isset($formDataIngredients[$recipeIngredient->getIngredient()->getId()])) {
                    $recipe->removeIngredient($recipeIngredient);
                }
            }

            // Adding ingredients
            foreach ($formDataIngredients as $dataIngredientId => $dataIngredientValue) {
                // If the ingredient is ever in the recipe
                if (!empty($recipeIngredients[$dataIngredientId])) {
                    /** @var RecipeHasIngredients $tmpRecipeIngredient */
                    $tmpRecipeIngredient = $recipeIngredients[$dataIngredientId];
                } else {
                    $ingredient = $this->getDoctrine()->getRepository('AppBundle:Ingredient')->find($dataIngredientId);
                    $tmpRecipeIngredient = new RecipeHasIngredients();
                    $tmpRecipeIngredient->setIngredient($ingredient);
                    $recipe->addIngredient($tmpRecipeIngredient);
                }

                $tmpRecipeIngredient
                    ->setAmount($dataIngredientValue['amount'])
                    ->setMeasureUnit($dataIngredientValue['measureUnit'])
                ;
            }

            if ($recipeForm->isValid()) {
                $recipeFormSuccessMessage = (!empty($recipe->getId())) ? 'recipe.edit.success' : 'recipe.add.success';

                $em->persist($recipe);
                $em->flush();
                $this->addFlash('notice', $this->get('translator')->trans($recipeFormSuccessMessage));
                return $this->redirectToRoute('app_recipe_view', ['slug' => $recipe->getSlug()]);
            }
        }

        return [
            'recipe'     => $recipe,
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
