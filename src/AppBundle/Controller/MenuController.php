<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends Controller
{
    /**
     * @Route("/menu")
     */
    public function indexAction(Request $request)
    {
        return $this->render('app/index.html.twig');
    }
}
