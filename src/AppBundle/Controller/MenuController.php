<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Menu;
use AppBundle\Form\MenuType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Route("{id}/modifier/", name="app_menu_edit")
     * @Security("has_role('ROLE_USER')")
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

            if ($menuForm->isValid()) {
                $menuFormSuccessMessage = (!empty($menu->getId())) ? 'menu.edit.success' : 'menu.add.success';

                $em = $this->getDoctrine()->getManager();
                $em->persist($menu);
                $em->flush();

                $this->addFlash('notice', $this->get('translator')->trans($menuFormSuccessMessage));
                return $this->redirectToRoute('app_menu_edit', ['id' => $menu->getId()]);
            }
        }

        return [
            'menuForm' => $menuForm->createView(),
            'menu'     => $menu
        ];
    }
}
