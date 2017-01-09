<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MenuController extends Controller
{
    /**
     * @Route("/menu")
     *
     * @Template("app/menu/add.html.twig")
     */
    public function listAction(Request $request)
    {
        //$menus = $this->getDoctrine()->getRepository('me')

        //return ['menus' => $menus];
    }
}
