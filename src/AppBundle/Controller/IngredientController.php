<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 24/11/16
 * Time: 13:21
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Ingredient;
use AppBundle\Form\IngredientType;
use AppBundle\Manager\IngredientManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IngredientController
 * @package AppBundle\Controller
 *
 * @Route("/ingredients/")
 */
class IngredientController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     *
     * @Route("ajouter/")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/ingredient/add.html.twig")
     */
    public function addAction(Request $request)
    {
        $ingredient = new Ingredient();
        $ingredientForm = $this->createForm(IngredientType::class, $ingredient);
        $ingredientForm->handleRequest($request);

        if ($ingredientForm->isSubmitted() && $ingredientForm->isValid()) {

            /** @var IngredientManager $ingredientManager */
            $ingredientManager = $this->get('app.manager.ingredient');
            if ($ingredientManager->ingredientAlreadyExists($ingredient)) {
                $message  = 'ingredient.add.exists';
                $redirect = false;
            } else {
                $ingredientManager->save($ingredient);
                $message  = 'ingredient.add.success';
                $redirect = true;
            }

            $message = $this->get('translator')->trans($message, ['%name%' => $ingredient->getName()]);
            $this->addFlash('notice', $message);

            if ($redirect) {
                return $this->redirectToRoute('app_ingredient_add');
            }
        }

        return [
            'ingredientForm' => $ingredientForm->createView(),
        ];
    }

}
