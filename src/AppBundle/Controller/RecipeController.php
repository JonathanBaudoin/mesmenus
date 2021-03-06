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
use AppBundle\Form\IngredientType;
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
     * @Route("{page}/", name="app_recipe_list", defaults={"page" = 1}, requirements={"page": "\d+"})
     * @Template("app/recipe/list.html.twig")
     */
    public function listAction($page = 1)
    {
        /** @var RecipeRepository $recipeRepository */
        $recipeRepository = $this->getDoctrine()->getRepository('AppBundle:Recipe');
        $qb               = $recipeRepository->findAllQueryBuilder($this->getUser());
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
                    ->setAmount(floatval($dataIngredientValue['amount']))
                    ->setMeasureUnit($dataIngredientValue['measureUnit'])
                ;
            }

            if ($recipeForm->isValid()) {
                $recipeFormSuccessMessage = (!empty($recipe->getId())) ? 'recipe.edit.success' : 'recipe.add.success';

                $em->persist($recipe);
                $em->flush();
                $this->addFlash('success', $this->get('translator')->trans($recipeFormSuccessMessage));
                return $this->redirectToRoute('app_recipe_view', ['slug' => $recipe->getSlug()]);
            }
        }

        $ingredientForm = $this->createForm(IngredientType::class, new Ingredient(), array(
            'method' => 'POST',
            'action' => $this->generateUrl('app_ingredient_addajax')
        ));

        return [
            'recipe'         => $recipe,
            'recipeForm'     => $recipeForm->createView(),
            'ingredientForm' => $ingredientForm->createView(),
        ];
    }

    /**
     * @param Recipe $recipe
     *
     * @return array
     *
     * @Route("{slug}/", name="app_recipe_view")
     * @Template("app/recipe/view.html.twig")
     */
    public function viewAction(Recipe $recipe)
    {
        return ['recipe' => $recipe];
    }

    /**
     * @param Request $request
     * @param Recipe  $recipe
     *
     * @return array|RedirectResponse
     *
     * @Route("{slug}/supprimer/", name="app_recipe_delete")
     * @Template("app/recipe/delete.html.twig")
     */
    public function deleteAction(Request $request, Recipe $recipe)
    {
        $deleteForm = $this->createFormBuilder()->getForm();
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recipe);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('recipe.delete.success'));
            return $this->redirectToRoute('app_recipe_list');
        }

        return [
            'recipe'     => $recipe,
            'deleteForm' => $deleteForm->createView()
        ];
    }
}
