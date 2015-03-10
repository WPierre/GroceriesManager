<?php

namespace WPierre\GroceriesManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WPierre\GroceriesManagerBundle\Entity\Item;
use WPierre\GroceriesManagerBundle\Form\ItemType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Item controller.
 *
 * @Route("/item")
 */
class ItemController extends Controller
{

    /**
     * Lists all Item entities.
     *
     * @Route("/", name="item")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('WPierreGroceriesManagerBundle:Item')->findAll();
        $entities = $em->createQuery('SELECT i FROM WPierreGroceriesManagerBundle:Item i, WPierreGroceriesManagerBundle:Category c WHERE i.category = c.id ORDER BY c.name ASC, i.name ASC')->getResult();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Item entity.
     *
     * @Route("/", name="item_create")
     * @Method("POST")
     * @Template("WPierreGroceriesManagerBundle:Item:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Item();
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
            $this->get('session')->getFlashBag()->add('success', 'L\'item "'.$entity->getName().'" a bien &eacute;t&eacute; cr&eacute;&eacute;.');
            return $this->redirect($this->generateUrl('item', array()));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Item entity.
     *
     * @param Item $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity, array(
            'action' => $this->generateUrl('item_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Item entity.
     *
     * @Route("/new", name="item_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Item();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Item entity.
     *
     * @Route("/{id}", name="item_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WPierreGroceriesManagerBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @Route("/{id}/edit", name="item_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WPierreGroceriesManagerBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
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
    * Creates a form to edit a Item entity.
    *
    * @param Item $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity, array(
            'action' => $this->generateUrl('item_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Item entity.
     *
     * @Route("/{id}", name="item_update")
     * @Method("PUT")
     * @Template("WPierreGroceriesManagerBundle:Item:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WPierreGroceriesManagerBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'L\'item "'.$entity->getName().'" a bien &eacute;t&eacute; enregistr&eacute;.');
            return $this->redirect($this->generateUrl('item', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Item entity.
     *
     * @Route("/{id}", name="item_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WPierreGroceriesManagerBundle:Item')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'L\'item "'.$entity->getName().'" a bien &eacute;t&eacute; supprim&eacute;.');
        }

        return $this->redirect($this->generateUrl('item'));
    }

    /**
     * Creates a form to delete a Item entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('item_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Returns a json array containing the items for a category
     *
     * @Route("/getByListAndCategory/{id_list}/{id_category}/", name="ItemsGetByListAndCategory")
     * @Method("GET")
     */
    public function getByListAndCategory($id_list, $id_category){
    	$em = $this->getDoctrine()->getManager();
    	//get all items for a category
    	$liste = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->findOneById($id_list);
    	//$liste_items = $em->getRepository('WPierreGroceriesManagerBundle:Item')->findByCategoryOrderedByName($id_category);
    	$liste_items = $em->createQuery('SELECT i FROM WPierreGroceriesManagerBundle:Item i WHERE i.category = :category ORDER BY i.name ASC')->setParameter('category',$id_category)->getResult();
    	//get the items
    	
    	$retour = Array();
    	foreach ($liste_items as $item){
    		$retour[] = Array(
    							'id'		=>	$item->getId(),
    							'name'		=>	$item->getName(),
    							'is_present' => $liste->hasItem($item)
    						);
    	}
    	
    	$response = new Response();
    	$response->setContent(json_encode($retour));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    /**
     * Returns a json array containing the items for a category
     *
     * @Route("/ListManagerHelper/{action}/{id_list}/{id_item}/", name="ItemsListManagerHelper")
     * @Method("GET")
     */
    public function ListManagerHelperAction($action,$id_list, $id_item){
    	$em = $this->getDoctrine()->getManager();
    	$liste = $em->getRepository('WPierreGroceriesManagerBundle:GroceriesList')->findOneById($id_list);
    	$item = $em->getRepository('WPierreGroceriesManagerBundle:Item')->findOneById($id_item);
    	$retour = false;
    	if ($action == "add"){
    		if (!$liste->hasItem($item)){
    			$liste->addItem($item);
    			$liste->setUpdated(new \DateTime());
    			$em->persist($liste);
    			$em->flush();
    			$retour = true;
    		}
    	} else {
    		if ($liste->hasItem($item)){
    			$liste->removeItem($item);
    			$liste->setUpdated(new \DateTime());
    			$em->persist($liste);
    			$em->flush();
    			$retour = true;
    		}
    	}
    	
    	$response = new Response();
    	$response->setContent(json_encode($retour));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
