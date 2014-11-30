<?php

namespace Contrate\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Contrate\BackendBundle\Form\FilterType;

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
        $total['Atleta']        = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 17)));
        $total['Ator']          = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 18)));
        $total['Atriz']         = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 19)));
        $total['Comediante']    = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 20)));
        $total['DJ']            = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 21)));
        $total['Jornalista']    = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 22)));
        $total['Show']          = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 23)));
        $total['Lutador']       = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 24)));
        $total['Celebridade']   = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 25)));
        $total['Teatro']        = count($em->getRepository("ContrateBackendBundle:Artist")->findBy(array('categoryId' => 26)));

        

        $categories = $em->getRepository('ContrateBackendBundle:Category')->findAll();
        return $this->render('ContrateFrontendBundle:Default:index.html.twig', array(
            'categories' => $categories, 'total' => $total));
    }

    public function searchAction($category)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Simple example
        $breadcrumbs->addItem("Home", $this->get("router")->generate("contrate_frontend_homepage"));
        $breadcrumbs->addItem("Artistas", $this->get("router")->generate("contrate_frontend_search"));

        $em = $this->getDoctrine()->getManager();
        
        $filterForm = $this->createForm(new FilterType());

        $qb = $em->createQueryBuilder();

        if ($this->getRequest()->getMethod() == 'POST') 
        {
            $filterForm->handleRequest($this->getRequest());

            $data = $filterForm->getData();
            $filters = array();
            
            $q = $data['q'];
            //$cat   = $data['category'];

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

            if (isset($data['category']) && !$data['category']->isEmpty()) {
                foreach ($data['category'] as $category)
                    $filters['category'][] = $category;
                //var_dump($filters['category']);
                $qb->andWhere('f.category in (:category)');
            }

            $qb->setParameters($filters);
            
            //$artists = $qb->getQuery()->getResult();
            //var_dump($qb->getQuery()->getSql());exit;
        }
        else {
            if ($category) {
                
                $qb->select('f')
                    ->from('ContrateBackendBundle:Artist', 'f')
                    ->where('f.category = :category')
                    ->orderBy('f.createdAt', 'desc');
                $filters['category'] = $category;
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
                $this->get('request')->query->get('page', 1)/*page number*/,
                10/*limit per page*/
            );

        

        return $this->render('ContrateFrontendBundle:Default:search.html.twig', 
            array('artists' => $artists, 'filterForm' => $filterForm->createView()));
    }
}
