<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AppController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @Template("app/index.html.twig")
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/a-propos")
     *
     * @Template("app/about.html.twig")
     */
    public function aboutAction()
    {

    }

    /**
     * @Route("/participer")
     *
     * @Template("app/participate.html.twig")
     */
    public function participateAction()
    {

    }
}
