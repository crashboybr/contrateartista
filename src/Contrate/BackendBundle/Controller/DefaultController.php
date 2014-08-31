<?php

namespace Contrate\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ContrateBackendBundle:Default:index.html.twig', array('name' => $name));
    }
}
