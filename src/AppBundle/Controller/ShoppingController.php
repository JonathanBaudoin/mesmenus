<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 19/01/17
 * Time: 14:47
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ShoppingController
 * @package AppBundle\Controller
 *
 * @Route("/liste-de-courses/")
 */
class ShoppingController extends Controller
{

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return array
     *
     * @Route("/{id}/")
     * @Security("has_role('ROLE_USER')")
     * @Template("app/shoppingList/view.html.twig")
     */
    public function viewAction(Request $request, $id)
    {
        $shoppingList = $this->getDoctrine()->getRepository('AppBundle:ShoppingList')->find($id);

        if (!$shoppingList || $shoppingList->getMenu()->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        return ['shoppingList' => $shoppingList];
    }
}