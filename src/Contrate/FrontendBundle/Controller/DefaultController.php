<?php

namespace Contrate\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Contrate\BackendBundle\Form\FilterType;
use Contrate\BackendBundle\Entity\Category;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Simple example
        $breadcrumbs->addItem("Home", $this->get("router")->generate("contrate_frontend_homepage"));

        // Example without URL
        //$breadcrumbs->addItem("Some text without link");

        // Example with parameter injected into translation "user.profile"
        //$breadcrumbs->addItem($txt, $url, array("%user%" => $user->getName()));

        $total['Apresentadora'] = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 15)));
        $total['Apresentador']  = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 16)));
        $total['Atleta']        = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 30)));
        $total['Ator']          = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 13)));
        $total['Atriz']         = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 14)));
        $total['Comediante']    = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 17)));
        $total['DJ']            = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 34)));
        $total['Jornalista']    = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 20)));
        $total['Show']          = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 36)));
        $total['Lutador']       = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 43)));
        $total['Celebridade']   = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 29)));
        $total['Teatro']        = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 35)));
        

        

        $categories = $em->getRepository('ContrateBackendBundle:Category')->findAll();

        return $this->render('ContrateFrontendBundle:Default:index.html.twig', array(
            'categories' => $categories, 'total' => $total));
    }

    public function searchAction(Category $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Simple example
        $breadcrumbs->addItem("Home", $this->get("router")->generate("contrate_frontend_homepage"));
        if ($category) $breadcrumbs->addItem($category->getName());

        $categories = $em->getRepository('ContrateBackendBundle:Category')->findAll();

        $em = $this->getDoctrine()->getManager();
        
        $filterForm = $this->createForm(new FilterType());

        $qb = $em->createQueryBuilder();

        $page = $this->get('request')->query->get('page', 1);

        if ($this->getRequest()->getMethod() == 'GET') 
        {
            //$filterForm->handleRequest($this->getRequest());

            //$page = $_POST['page'];

            //$data = $filterForm->getData();
            $filters = array();
            //if (!$data['q'] && isset($_POST['q']))
            //    $data['q'] = $_POST['q']; 
            $q = isset($_GET['q']) ? $_GET['q'] : null;
            //$cat   = $data['category'];
            //var_dump($_POST);exit;

            $qb->select('f')
                ->from('ContrateBackendBundle:Artist', 'f')
                //->where('f.category = :category')
                ->orderBy('f.createdAt', 'desc');

            if ($q) {
                $q = "%" . $q . "%";
                $filters['name'] = $q;
                $qb->andWhere('f.name like :name');

                //$artists = $em->getRepository('ContrateBackendBundle:Artist')->findBy(array('name' => $query));
            }

            //if (isset($data['category']) && !$data['category']->isEmpty()) {
            if (isset($_GET['cat'])) {
                foreach ($_GET['cat'] as $category)
                    $filters['category'][] = $category;
                    //$filters['category'] = $data['category'];
                //var_dump($filters['category']);
                $qb->andWhere('f.category in (:category)');
                //$qb->andWhere('f.category = :category');
            }
            //var_dump($filters);exit;
            $qb->setParameters($filters);
            
            //$artists = $qb->getQuery()->getResult();
            //var_dump($qb->getQuery()->getSql());exit;
        }
        else {

            $page = $this->get('request')->query->get('page', 1);
            $cat = $this->get('request')->query->get('cat');
            

            if ($cat) {
                
                $qb->select('f')
                    ->from('ContrateBackendBundle:Artist', 'f')
                    ->where('f.category = :category')
                    ->orderBy('f.createdAt', 'desc');
                $filters['category'] = $cat;
                $qb->setParameters($filters);
                //$artists = $em->getRepository('ContrateBackendBundle:Artist')->findByCategory($category);
            }
            else {
                //$artists = $em->getRepository('ContrateBackendBundle:Artist')->findAll();
                $qb->select('f')
                    ->from('ContrateBackendBundle:Artist', 'f')
                    ->orderBy('f.createdAt', 'desc');
            }
        }


        $paginator  = $this->get('knp_paginator');
        $artists = $paginator->paginate(
                $qb->getQuery(),
                $page/*page number*/,
                10/*limit per page*/
            );
        $selcats = null;
        if (isset($_GET['cat']))
            foreach ($_GET['cat'] as $cat) {
                $selcats[$cat] = 1;
            }
        


        return $this->render('ContrateFrontendBundle:Default:search.html.twig', 
            array('artists' => $artists, 'filterForm' => $filterForm->createView(), 'page' => $page, 
                'categories' => $categories, 'selcats' => $selcats ));
    }

    public function quemAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("contrate_frontend_homepage"));
        $breadcrumbs->addItem("Quem somos");

        return $this->render('ContrateFrontendBundle:Default:quem-somos.html.twig');
    }

    public function duvidasAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("contrate_frontend_homepage"));
        $breadcrumbs->addItem("Dúvidas frequentes");
        return $this->render('ContrateFrontendBundle:Default:duvidas.html.twig');
    }

    public function comoAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("contrate_frontend_homepage"));
        $breadcrumbs->addItem("Como funciona");
        return $this->render('ContrateFrontendBundle:Default:como-funciona.html.twig');
    } 

}
