<?php

namespace Wit\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wit\ModelBundle\Entity\Message;
use Wit\ModelBundle\Form\MessageType;

/**
 * Message controller.
 *
 * @Route("/message")
 */
class MessageController extends Controller
{

    /**
     * Lists all Messages for current logged in user
     *
     * @Route("/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        //get logged in user id
        $user = $this->get('security.context')->getToken()->getUser()->getId();

        $em = $this->getDoctrine()->getManager();

        $messagesEntities = $em->getRepository('ModelBundle:Message')->findBy(array('toUser'=>$user), array('id'=>'DESC'));

        $users = $em->getRepository('ModelBundle:User')->findAll();

        return array(
            'messagesEntities' => $messagesEntities,
            'users' => $users,
        );
    }

    /**
     * Lists all Sent Messages for current logged in user
     *
     * @Route("/sent")
     * @Method("GET")
     * @Template()
     */
    public function sentAction()
    {
        //get logged in user id
        $user = $this->get('security.context')->getToken()->getUser()->getId();

        $em = $this->getDoctrine()->getManager();

        $messagesEntities = $em->getRepository('ModelBundle:Message')->findBy(array('fromUser'=>$user), array('id'=>'DESC'));

        $users = $em->getRepository('ModelBundle:User')->findAll();

        return array(
            'messagesEntities' => $messagesEntities,
            'users' => $users,
        );
    }

    /**
     * Creates a new Message entity.
     *
     * @Route("/", name="message_create")
     * @Method("POST")
     * @Template("ModelBundle:Message:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Message();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('message_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Message entity.
     *
     * @param Message $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Message $entity)
    {
        $form = $this->createForm(new MessageType(), $entity, array(
            'action' => $this->generateUrl('message_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Message entity.
     *
     * @Route("/new")
     * @Method("POST")
     * @Template()
     */
    public function newAction(Request $request)
    {
        if ($request->isMethod('POST')){
            
            //getting subject fields..
            $newMsgUser  = $this->get('request')->request->get('newMsgUser');
            $newMsgSubject  = $this->get('request')->request->get('newMsgSubject');
            $newMsgBody  = $this->get('request')->request->get('newMsgBody'); //need to fix this field fron view..!

            $em = $this->getDoctrine()->getManager();

            $toUserEntity = $em->getRepository('ModelBundle:User')->find($newMsgUser);
            $fromUserEntity = $em->getRepository('ModelBundle:User')->find($this->get('security.context')->getToken()->getUser()->getId()); //logged in user

            $msg = new Message();

            $msg->setToUser($toUserEntity);
            $msg->setFromUser($fromUserEntity);
            $msg->setSubject($newMsgSubject);
            $msg->setBody($newMsgBody);
            $msg->setDateCreated(new \DateTime());

            $msg->setIsDeletedByFromUser(0);
            $msg->setIsDeletedByToUser(0);

            $msg->setIsRead(0);

            $em->persist($msg);
            $em->flush();

            echo true;
            exit;
        }else{
            echo false;
            exit;
        }
    }

    /**
     * Deletes a entity.
     *
     * @Route("/delete/{id}")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        // Need security here.. to be deleted for online user only

        if($id){

            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('ModelBundle:Message')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $entity->setIsDeletedByToUser(1);

            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Message was deleted successfully..');
            return $this->redirect($this->generateUrl('wit_user_message_index'));
        }
    }

    /**
     * Finds and displays a Message entity.
     *
     * @Route("/{id}", name="message_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:Message')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Message entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Message entity.
     *
     * @Route("/{id}/edit", name="message_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:Message')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Message entity.');
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
    * Creates a form to edit a Message entity.
    *
    * @param Message $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Message $entity)
    {
        $form = $this->createForm(new MessageType(), $entity, array(
            'action' => $this->generateUrl('message_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Message entity.
     *
     * @Route("/{id}", name="message_update")
     * @Method("PUT")
     * @Template("ModelBundle:Message:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:Message')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Message entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('message_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Message entity.
     *
     * Route("/{id}", name="message_delete")
     * Method("DELETE")
     */
    // public function deleteAction(Request $request, $id)
    // {
    //     $form = $this->createDeleteForm($id);
    //     $form->handleRequest($request);

    //     if ($form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $entity = $em->getRepository('ModelBundle:Message')->find($id);

    //         if (!$entity) {
    //             throw $this->createNotFoundException('Unable to find Message entity.');
    //         }

    //         $em->remove($entity);
    //         $em->flush();
    //     }

    //     return $this->redirect($this->generateUrl('message'));
    // }

    /**
     * Creates a form to delete a Message entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('message_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
