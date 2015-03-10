<?php

namespace WPierre\GroceriesManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WPierre\GroceriesManagerBundle\Entity\GroceriesList;
use WPierre\GroceriesManagerBundle\Form\GroceriesListType;
use Symfony\Component\HttpFoundation\Response;

/**
 * GroceriesList controller.
 *
 * @Route("/grocerieslist")
 */
class GroceriesListController extends Controller
{

    /**
     * Lists all GroceriesList entities.
     *
     * @Route("/", name="grocerieslist")
     * @Method("GET")
     * @Template("WPierreGroceriesManagerBundle:GroceriesList:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->findAll();
        $entities = $em->createQuery('SELECT l FROM WPierreGroceriesManagerBundle:GroceriesList l ORDER BY l.updated DESC')->getResult();
        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new GroceriesList entity.
     *
     * @Route("/", name="grocerieslist_create")
     * @Method("POST")
     * @Template("WPierreGroceriesManagerBundle:GroceriesList:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new GroceriesList();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
        	if ($entity->getCreated() == null){
        		$entity->setCreated(new \DateTime());
        	}
        	$entity->setUpdated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La liste "'.$entity->getName().'" a bien &eacute;t&eacute; cr&eacute;&eacute;e.');
            return $this->redirect($this->generateUrl('grocerieslist', array()));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a GroceriesList entity.
     *
     * @param GroceriesList $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GroceriesList $entity)
    {
    	/*
        $form = $this->createForm(new GroceriesListType(), $entity, array(
            'action' => $this->generateUrl('grocerieslist_create'),
            'method' => 'POST',
        ));
        */
        $form = $this->createFormBuilder($entity)
        	->setAction($this->generateUrl('grocerieslist_create'))
        	->setMethod('POST')
        	->add('name','text')
        	->add('id','hidden')
        	->getForm();

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new GroceriesList entity.
     *
     * @Route("/new", name="grocerieslist_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new GroceriesList();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a GroceriesList entity.
     *
     * @Route("/{id}", name="grocerieslist_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroceriesList entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a GroceriesList entity.
     *
     * @Route("/printable/{id}", name="grocerieslist_printable")
     * @Method("GET")
     * @Template()
     */
    public function printableAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->find($id);
    
    	$items = $em->createQuery('SELECT i FROM WPierreGroceriesManagerBundle:Category c, WPierreGroceriesManagerBundle:Item i WHERE i.category = c.id ORDER BY c.name ASC, i.name ASC')->getResult();
    	//echo "il y a un total de ".count($items)." objets";
    	$tableau = Array();
    	foreach ($items as $item){
    		if ($entity->hasItem($item)){
    			$tableau[$item->getCategory()->getName()][] = $item;
    		}
    	}
    	//var_dump($tableau);
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find GroceriesList entity.');
    	}
    
    	return array(
    			'entity'    => $entity,
    			'tableau' 	=> $tableau,
    	);
    }
    
    /**
     * Finds and displays a GroceriesList entity.
     *
     * @Route("/manage/{id}", name="grocerieslist_manage")
     * @Method("GET")
     * @Template()
     */
    public function manageAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find GroceriesList entity.');
    	}
    
    	$categories = $em->createQuery('SELECT c FROM WPierreGroceriesManagerBundle:Category c ORDER BY c.name')->getResult();
    
    	return array(
    			'entity'      => $entity,
    			'categories' => $categories,
    	);
    }

    /**
     * Empties a GroceriesList
     *
     * @Route("/empty/{id}", name="grocerieslist_empty")
     * @Method("GET")
     * @Template()
     */
    public function emptyGroceriesListAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->find($id);
    	
    	$entity->removeAllItems();
    	$entity->setCommentaire(null);
    	$em->persist($entity);
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('grocerieslist_manage', array('id'=> $id)));
    }
    
    /**
     * Displays a form to edit an existing GroceriesList entity.
     *
     * @Route("/{id}/edit", name="grocerieslist_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroceriesList entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a GroceriesList entity.
    *
    * @param GroceriesList $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(GroceriesList $entity)
    {
        /*$form = $this->createForm(new GroceriesListType(), $entity, array(
            'action' => $this->generateUrl('grocerieslist_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));*/
    	$form = $this->createFormBuilder($entity)
    	->setAction($this->generateUrl('grocerieslist_update', array('id' => $entity->getId())))
    	->setMethod('PUT')
    	->add('name','text')
    	//->add('id','hidden')
    	->getForm();
    	 

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing GroceriesList entity.
     *
     * @Route("/{id}", name="grocerieslist_update")
     * @Method("PUT")
     * @Template("WPierreGroceriesManagerBundle:GroceriesList:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroceriesList entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        	$entity->setUpdated(new \DateTime());
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La cat&eacute;gorie "'.$entity->getName().'" a bien &eacute;t&eacute; enregistr&eacute;e.');
            return $this->redirect($this->generateUrl('grocerieslist', array()));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a GroceriesList entity.
     *
     * @Route("/{id}", name="grocerieslist_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GroceriesList entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La cat&eacute;gorie "'.$entity->getName().'" a bien &eacute;t&eacute; supprim&eacute;e.');
        }

        return $this->redirect($this->generateUrl('grocerieslist'));
    }

    /**
     * Creates a form to delete a GroceriesList entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grocerieslist_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Returns a json array containing the items for a category
     *
     * @Route("/modifyCommentaire/{id_entity}/", name="modifyCommentaire")
     * @Method("POST")
     */
    public function ModifyCommentaire($id_entity){
    	$em = $this->getDoctrine()->getManager();
    	//get all items for a category
    	
    	$request = $this->container->get('request');
    	$commentaire = $request->request->get('commentaire');
    	
    	$liste = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->findOneById($id_entity);
		$liste->setCommentaire($commentaire);
		$liste->setUpdated(new \DateTime());
		$em->persist($liste);
		$em->flush();

		$retour = true;
		
		$response = new Response();
    	$response->setContent(json_encode($retour));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
