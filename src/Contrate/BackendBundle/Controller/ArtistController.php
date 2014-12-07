<?php

namespace Contrate\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Contrate\BackendBundle\Entity\Artist;
use Contrate\BackendBundle\Entity\ArtistImage;
use Contrate\BackendBundle\Entity\Contact;
use Contrate\BackendBundle\Form\ArtistType;
use Contrate\BackendBundle\Form\ArtistImageType;
use Contrate\BackendBundle\Form\ContactType;

/**
 * Artist controller.
 *
 */
class ArtistController extends Controller
{

    /**
     * Lists all Artist entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ContrateBackendBundle:Artist')->findAll();

        return $this->render('ContrateBackendBundle:Artist:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Artist entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Artist();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $me = $this->getUser();

        if ($form->isValid()) {
            $user = $this->getUser();
            if (!$user) {
                throw $this->createNotFoundException('Voce precisa estar logado!');
            }

            foreach ($entity->getArtistImages() as $artistImage)
            {
                
                $artistImage->setArtists($entity);
                $file = $artistImage->getPic();
                $artistImage->setPic(null);
                $artistImage->setFile($file);
            }

            $em = $this->getDoctrine()->getManager();
            $entity->setUser($user);
            $entity->setVisit(0);
            $entity->setStatus(-1);
            $em->persist($entity);
            $em->flush();

            $img = $entity->getArtistImages();

            if ($img) 
            {
                $entity->setDefaultImg($img[0]->getPic());
                $em->persist($entity);
                $em->flush();
            }
            $this->get('send_mail')->sendEmail($entity, 'newartist');
            $this->get('session')->getFlashBag()->add(
                        'success',
                        'Artista cadastrado com sucesso! Estamos revisando as informações, aguarde a confirmação por email! Obrigado!');

            return $this->redirect($this->generateUrl('artist_new'));
        }

        return $this->render('ContrateBackendBundle:Artist:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'me'   => $me
        ));
    }

    /**
     * Creates a form to create a Artist entity.
     *
     * @param Artist $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Artist $entity)
    {
        $artist_image = new ArtistImage();
        $entity->addArtistImage($artist_image);

        $artist_image = new ArtistImage();
        $entity->addArtistImage($artist_image);

        $artist_image = new ArtistImage();
        $entity->addArtistImage($artist_image);

        $form = $this->createForm(new ArtistType(), $entity, array(
            'action' => $this->generateUrl('artist_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Artist entity.
     *
     */
    public function newAction()
    {
        $entity = new Artist();
        $form   = $this->createCreateForm($entity);
        $me = $this->getUser();
        //var_dump($me);exit;

        return $this->render('ContrateBackendBundle:Artist:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'me' => $me
        ));
    }

    /**
     * Finds and displays a Artist entity.
     *
     */
    public function showAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ContrateBackendBundle:Artist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Artist entity.');
        }
        $entity->setVisit($entity->getVisit() + 1);
        $em->persist($entity);
        $em->flush();
        
        $contact = new Contact();
        $form = $this->createForm(new ContactType(), $contact, array(
            'action' => $this->generateUrl('artist_create_contact'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enviar'));

        $form->add('artistId', 'hidden', array('data' => $id));

        $breadcrumbs = $this->get("white_october_breadcrumbs");

        $breadcrumbs->addItem("Home", $this->get("router")->generate("contrate_frontend_homepage"));

        $breadcrumbs->addItem($entity->getCategory()->getName(), $this->get("router")
            ->generate("contrate_frontend_search", array('category' => $entity->getCategory()->getId())));


        $breadcrumbs->addItem($entity->getName());

        $total_imgs = count($entity->getArtistImages());
        return $this->render('ContrateBackendBundle:Artist:show.html.twig', array(
            'artist'        => $entity,
            'form'          => $form->createView(),
            'total_imgs'    => $total_imgs,
        ));
    }

    public function createContactAction(Request $request)
    {
        $entity = new Contact();
        $em = $this->getDoctrine()->getManager();
        
        $artist_id  = $request->get('contrate_backendbundle_contact')['artistId'];
        
        $artist = $em->getRepository('ContrateBackendBundle:Artist')->find($artist_id);
        
        $entity->setArtist($artist);

        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('artist_create_contact'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Enviar'));
        $form->add('artistId', 'hidden', array('data' => $artist_id));
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $entity->setStatus(-1);
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('success', 'Formulário enviado com sucesso!');

            $this->get('send_mail')->sendEmail($entity, 'adreply');

            return $this->redirect($this->generateUrl('artist_show', array('id' => $artist_id)));
        }

        $this->get('session')->getFlashBag()->set('error', 'Preencha todos os campos do formulário!');

        $breadcrumbs = $this->get("white_october_breadcrumbs");

        $breadcrumbs->addItem("Home", $this->get("router")->generate("contrate_frontend_homepage"));

        $breadcrumbs->addItem($artist->getCategory()->getName(), $this->get("router")
            ->generate("contrate_frontend_search", array('category' => $artist->getCategory()->getId())));


        $breadcrumbs->addItem($artist->getName());

        return $this->render('ContrateBackendBundle:Artist:show.html.twig', array(
            'entity' => $entity,
            'artist' => $artist,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Artist entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ContrateBackendBundle:Artist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Artist entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ContrateBackendBundle:Artist:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Artist entity.
    *
    * @param Artist $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Artist $entity)
    {
        $form = $this->createForm(new ArtistType(), $entity, array(
            'action' => $this->generateUrl('artist_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Artist entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ContrateBackendBundle:Artist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Artist entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('artist_edit', array('id' => $id)));
        }

        return $this->render('ContrateBackendBundle:Artist:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Artist entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ContrateBackendBundle:Artist')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Artist entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('artist'));
    }

    /**
     * Creates a form to delete a Artist entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('artist_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
