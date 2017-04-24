<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 19/01/17
 * Time: 14:47
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Menu;
use AppBundle\Entity\ShoppingListIngredients;
use AppBundle\Form\ShoppingListIngredientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ShoppingController
 * @package AppBundle\Controller
 *
 * @Route("/menu/")
 */
class ShoppingListController extends Controller
{

    /**
     * @param Request $request
     * @param Menu    $menu
     *
     * @return array|RedirectResponse
     *
     * @Route("{id}/liste-de-courses/ajouter/produit")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/shoppingList/add.html.twig")
     */
    public function addProductAction(Request $request, Menu $menu)
    {
        if ($menu->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $shoppingListIngredient = new ShoppingListIngredients();
        $shoppingListIngredient
            ->setMenu($menu)
            ->setExtraMenu(true)
        ;

        $shoppingListForm = $this->createForm(ShoppingListIngredientType::class, $shoppingListIngredient);
        $shoppingListForm->handleRequest($request);

        if ($shoppingListForm->isSubmitted()) {
            if ($shoppingListForm->isValid()) {

                $shoppingListIngredient->setIngredientName();

                $menu->addShoppingListIngredient($shoppingListIngredient);

                $em = $this->getDoctrine()->getManager();
                $em->persist($menu);
                $em->flush();

                $this->addFlash('success', $this->get('translator')->trans('shoppingList.ingredient.added'));
                return $this->redirectToRoute('app_shoppinglist_view', ['id' => $menu->getId()]);
            }
        }

        return [
            'menu'             => $menu,
            'shoppingListForm' => $shoppingListForm->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param Menu    $menu
     *
     * @return array|RedirectResponse
     *
     * @Route("{id}/liste-de-courses/modifier")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/shoppingList/edit.html.twig")
     */
    public function editAction(Request $request, Menu $menu)
    {
        if ($menu->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        return [

        ];
    }

    /**
     * @param Menu $menu
     *
     * @return RedirectResponse
     *
     * @Route("{id}/liste-de-courses/generer/")
     * @Security("has_role('ROLE_USER')")
     */
    public function generateAction(Menu $menu)
    {
        if ($menu->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $this->get('app.manager.shopping_list')->saveShoppingListFromRecipesMenu($menu);

        return $this->redirectToRoute('app_shoppinglist_view', ['id' => $menu->getId()]);
    }

    /**
     * @param Request $request
     * @param Menu    $menu
     *
     * @return array
     *
     * @Route("{id}/liste-de-courses/voir/")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/shoppingList/view.html.twig")
     */
    public function viewAction(Request $request, Menu $menu)
    {
        if ($menu->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $shoppingList = $this
            ->get('doctrine')
            ->getRepository('AppBundle:ShoppingListIngredients')
            ->findByMenu($menu)
        ;

        return [
            'menu'         => $menu,
            'shoppingList' => $shoppingList,
        ];
    }

    /**
     * @param Request $request
     * @param Menu    $menu
     * @param int     $productId
     *
     * @return JsonResponse
     *
     * @Route("{id}/liste-de-courses/voir/ajax/product_cart/{productId}", name="app_shoppinglist_add_product_to_cart", options={"expose"=true})
     * @Security("has_role('ROLE_USER')")
     */
    public function addProductToCartAction(Request $request, Menu $menu, $productId)
    {
        if ($request->isXmlHttpRequest()) {
            if ($menu && $this->getUser()) {
                if ($menu->getUser() === $this->getUser()) {
                    $shoppingListRepository = $this->getDoctrine()->getRepository('AppBundle:ShoppingListIngredients');
                    $product                = $shoppingListRepository->findProductByMenu($menu, $productId);

                    if ($product) {
                        if ($product->isInCart()) {
                            $product->setInCart(false);
                            $response = 'removed';
                        } else {
                            $product->setInCart(true);
                            $response = 'added';
                        }

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($product);
                        $em->flush();
                        return new JsonResponse($response);
                    }
                }
            }
        }

        return new JsonResponse('error');
    }

    /**
     * @param Request $request
     * @param Menu    $menu
     * @param         $productId
     *
     * @return array|RedirectResponse|NotFoundHttpException
     *
     * @Route("{id}/liste-de-courses/modifier/produit/{productId}", name="app_shoppinglist_edit_product_to_cart")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/shoppingList/edit_product.html.twig")
     */
    public function editProductToCartAction(Request $request, Menu $menu, $productId)
    {
        if ($menu->getUser() === $this->getUser()) {
            $shoppingListRepository = $this->getDoctrine()->getRepository('AppBundle:ShoppingListIngredients');
            $product                = $shoppingListRepository->findProductByMenu($menu, $productId);

            if ($product) {
                $removeProductForm = $this->createFormBuilder($product)->getForm();

                $productForm = $this->createForm(ShoppingListIngredientType::class, $product);
                $productForm
                    ->remove('ingredient')
                    ->remove('ingredientName')
                ;

                $removeProductForm->handleRequest($request);
                $productForm->handleRequest($request);

                $em = $this->getDoctrine()->getManager();

                if ($removeProductForm->isSubmitted() && $removeProductForm->isValid()) {
                    $em->remove($product);
                    $em->flush();

                    $this->addFlash('success', $this->get('translator')->trans(
                        'shoppingList.ingredient.deleted',
                        ['%product%' => $product->getIngredientName()]
                    ));
                    return $this->redirectToRoute('app_shoppinglist_view', ['id' => $menu->getId()]);
                }

                if ($productForm->isSubmitted()) {
                    if ($productForm->isValid()) {
                        $em->persist($product);
                        $em->flush();
                    }
                }

                return [
                    'menu'              => $menu,
                    'product'           => $product,
                    'productForm'       => $productForm->createView(),
                    'removeProductForm' => $removeProductForm->createView(),
                ];
            }
        }

        return $this->createNotFoundException();
    }
}
