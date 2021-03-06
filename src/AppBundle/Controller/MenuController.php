<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meal;
use AppBundle\Entity\Menu;
use AppBundle\Form\MenuType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MenuController
 * @package AppBundle\Controller
 *
 * @Route("/menu/")
 */
class MenuController extends Controller
{
    /**
     * @param int     $page
     *
     * @return array
     *
     * @Route("{page}/", defaults={"page" = 1}, requirements={"page": "\d+"})
     * @Security("has_role('ROLE_USER')")
     * @Template("app/menu/list.html.twig")
     */
    public function listAction($page = 1)
    {
        $qb         = $this->getDoctrine()->getRepository('AppBundle:Menu')->findByUserQueryBuilder($this->getUser());
        $pagination = $this->get('app.services.paginator')->paginate($qb, $page);

        return ['pagination' => $pagination];
    }

    /**
     * @param Request $request
     * @param null    $id
     *
     * @return array|RedirectResponse
     *
     * @Route("ajouter/", name="app_menu_add")
     * @Route("{id}/modifier/", name="app_menu_edit", requirements={"page": "\d+"})
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @Template("app/menu/add.html.twig")
     */
    public function addAction(Request $request, $id = null)
    {
        if (!is_null($id)) {
            $menu = $this->getDoctrine()->getRepository('AppBundle:Menu')->find($id);

            if (!$menu || $menu->getUser() !== $this->getUser()) {
                throw $this->createNotFoundException();
            }
        } else {
            $menu = new Menu();
            $menu->setUser($this->getUser());
        }

        $menuForm = $this->createForm(MenuType::class, $menu);
        $menuForm->handleRequest($request);

        if ($menuForm->isSubmitted()) {
            $startDate  = clone $menu->getDateStart();

            if (!is_null($menu->getId())) {
                $autoFilled = $menuForm->get('autoFilled')->getData();
                $usedRecipes   = [];
                $unfilledMeals = 0;

                // For each day
                for ($d = $startDate; $d <= $menu->getDateEnd(); $d->add(new \DateInterval('P1D'))) {
                    // Lunch and dinner
                    foreach (Meal::$mealTypes as $mealType) {
                        if (!isset($menuForm['meal_'.$d->format('Y-m-d').'_'.$mealType])) {
                            continue;
                        }

                        // Get recipes from current day and current meal
                        /** @var ArrayCollection $recipes */
                        $recipes = $menuForm['meal_'.$d->format('Y-m-d').'_'.$mealType]->getData();

                        if (empty($recipes->getValues())) {
                            $unfilledMeals++;
                        } else {
                            $usedRecipes = array_merge($usedRecipes, $recipes->getValues());
                        }

                        $d2 = clone $d;
                        ($mealType === 'lunch') ? $d2->setTime(12, 00) : $d2->setTime(19, 00);

                        $meal = $this->getDoctrine()->getRepository('AppBundle:Meal')->findOneBy([
                            'menu' => $menu,
                            'date' => $d2,
                            'type' => $mealType
                        ]);

                        if (!$meal) {
                            $meal = new Meal();
                        }

                        $meal
                            ->setDate($d2)
                            ->setType($mealType)
                            ->setRecipes($recipes)
                        ;

                        $menu->addMeal($meal);
                    }
                }

                // If empty meals must be auto filled
                if (!empty($autoFilled)) {
                    $recipeRepo        = $this->getDoctrine()->getRepository('AppBundle:Recipe');
                    $autoFilledRecipes = [];

                    foreach ($autoFilled as $recipeType) {
                        $qb = $recipeRepo
                            ->findAllQueryBuilder($this->getUser())
                            ->andWhere('r.recipeType = :recipeType')
                            ->setParameter('recipeType', $recipeType)
                            ->setMaxResults($unfilledMeals)
                            ->orderBy('RAND()')
                        ;

                        if (!empty($usedRecipes)) {
                            $qb
                                ->andWhere('r.id NOT IN (:usedRecipes)')
                                ->setParameter('usedRecipes', array_unique($usedRecipes))
                            ;
                        }

                        $autoFilledRecipes[$recipeType] = $qb->getQuery()->getResult();
                    }

                    // If recipes have be found
                    if (!empty($autoFilledRecipes)) {
                        // For each Menu meal
                        foreach ($menu->getMeals() as $currentMeal) {
                            // If meal has no recipes
                            if (empty($currentMeal->getRecipes()->getValues())) {
                                // For each auto filled recipe
                                foreach ($autoFilledRecipes as $autoFilledRecipeKey => $autoFilledRecipeValue) {
                                    if (!empty($autoFilledRecipeValue)) {
                                        foreach ($autoFilledRecipeValue as $recipeKey => $recipe) {
                                            $currentMeal->addRecipe($recipe);
                                            unset($autoFilledRecipes[$autoFilledRecipeKey][$recipeKey]);

                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($menuForm->isValid()) {
                $menuFormSuccessMessage = (!empty($menu->getId())) ? 'menu.edit.success' : 'menu.add.success';

                $em = $this->getDoctrine()->getManager();
                $em->persist($menu);
                $em->flush();

                $this->addFlash('success', $this->get('translator')->trans($menuFormSuccessMessage));
                return $this->redirectToRoute('app_menu_edit', ['id' => $menu->getId()]);
            }
        } else {
            if (!is_null($menu->getId())) {
                // Form is manually setted with meals menu values
                /** @var Meal $meal */
                foreach ($menu->getMeals() as $meal) {
                    if (!empty($menuForm['meal_' . $meal->getDate()->format('Y-m-d') . '_' . $meal->getType()])) {
                        $menuForm['meal_' . $meal->getDate()->format('Y-m-d') . '_' . $meal->getType()]->setData($meal->getRecipes());
                    }
                }
            }
        }

        return [
            'menuForm' => $menuForm->createView(),
            'menu'     => $menu
        ];
    }

    /**
     * @param Menu $menu
     *
     * @return array
     *
     * @Route("{id}/voir/", name="app_menu_view")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/menu/view.html.twig")
     */
    public function viewAction(Menu $menu)
    {
        if ($menu->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $meals = [];
        /** @var Meal $meal */
        foreach ($menu->getMeals() as $meal) {
            $meals[$meal->getDate()->format('Y-m-d')][$meal->getType()] = $meal->getRecipes();
        }

        return [
            'menu'  => $menu,
            'meals' => $meals,
        ];
    }

    /**
     * @param Menu $menu
     *
     * @return Response
     *
     * @Route("{id}/voir-pdf/", name="app_menu_view_pdf")
     * @Security("has_role('ROLE_USER')")
     */
    public function exportPdfAction(Menu $menu)
    {
        if ($menu->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $meals = [];
        /** @var Meal $meal */
        foreach ($menu->getMeals() as $meal) {
            $meals[$meal->getDate()->format('Y-m-d')][$meal->getType()] = $meal->getRecipes();
        }

        $shoppingList = $this
            ->get('doctrine')
            ->getRepository('AppBundle:ShoppingListIngredients')
            ->findByMenu($menu)
        ;

        $html = $this->renderView(':app/menu:view_pdf.html.twig', [
            'menu'         => $menu,
            'meals'        => $meals,
            'shoppingList' => $shoppingList,
        ]);

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="menu.pdf"'
            ]
        );
    }
}
