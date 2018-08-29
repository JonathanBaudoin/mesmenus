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
     * @return array|RedirectResponse
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
            $this->addFlash('success', $message);

            if ($redirect) {
                return $this->redirectToRoute('app_ingredient_add');
            }
        }

        return [
            'ingredientForm' => $ingredientForm->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse|null
     *
     * @Route("ajax/ajouter/")
     * @Security("has_role('ROLE_USER')")
     */
    public function addAjaxAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            /** @var IngredientManager $ingredientManager */
            $ingredientManager = $this->get('app.manager.ingredient');
            $ingredientName    = $request->query->get('ingredientName');

            if (null !== $ingredientName) {
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientName);

                if ($ingredientManager->ingredientAlreadyExists($ingredient)) {
                    $return  = 'error';
                    $message = 'ingredient.add.exists';
                } else {
                    $ingredientManager->save($ingredient);
                    $return  = 'success';
                    $message = 'ingredient.add.success';
                }

                $message = $this->get('translator')->trans($message, ['%name%' => $ingredient->getName()]);

                return new JsonResponse([
                    'return'         => $return,
                    'message'        => $message,
                    'ingredientId'   => $ingredient->getId(),
                    'ingredientName' => $ingredient->getName(),
                ]);
            }
        }

        return null;
    }

}
