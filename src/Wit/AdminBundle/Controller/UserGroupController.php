<?php

namespace Wit\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;

use Wit\ModelBundle\Entity\UserGroup;
use Wit\ModelBundle\Entity\GroupUser;

use Wit\AdminBundle\Form\UserGroupType;

/**
 * User Group controller.
 *
 * @Route("/user-group")
 */
class UserGroupController extends Controller
{
    /**
     * This action will serve as sole action for Add/ Edit and Delete record
     * 
     * @Route("/")
     * @Route("/edit/{id}", defaults={"id" = null})
     * 
     * @Template()
     */
    public function indexAction($id=null)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ModelBundle:UserGroup')->findBy(array(), array('id' => 'DESC'));

        //form to create
        $addform = $this->createCreateForm(new UserGroup());

        //if user wants to edit a record
        if($id){
            $editEntity = $em->getRepository('ModelBundle:UserGroup')->find($id);
            if (!$editEntity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $editForm = $this->createEditForm($editEntity);

            return array(
                'entities'      => $entities,
                'addForm'       => $addform->createView(),
                'editEntity'    => $editEntity,
                'editForm'      => $editForm->createView(),
            );
        }else{
            return array(
                'entities'      => $entities,
                'addForm'       => $addform->createView(),
            );
        } 
    }

    /**
     * Creates a form to create an entity.
     *
     * @param UserGroup $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(UserGroup $entity)
    {
        $form = $this->createForm(new UserGroupType(), $entity, array(
            'action' => $this->generateUrl('wit_admin_usergroup_new'),
            'method' => 'POST',
            'attr' => array(
                'id'    => 'add-new-form',
            )
        ));

        return $form;
    }

    /**
     * Create a new entity.
     *
     * @Route("/new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        //check if form was submitted
        if ($request->isMethod('POST')){

            $entity = new UserGroup();

            //addform to create an entity.
            $addform = $this->createCreateForm($entity);

            $addform->handleRequest($request);

            if ($addform->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                /*
                 * record was created..
                 * Redirect user
                 */
                $this->get('session')->getFlashBag()->set('error', 'New record was added successfully..');
                return $this->redirect($this->generateUrl('wit_admin_usergroup_index'));
            }

        }
    }

    /**
     * Creates a form to edit an entity.
     *
     * @param UserGroup $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(UserGroup $entity)
    {
        $form = $this->createForm(new UserGroupType(), $entity, array(
            'action' => $this->generateUrl('wit_admin_usergroup_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'id' => 'update-country-form',
                'novalidate' => 'novalidate',
            )
        ));

        return $form;
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:UserGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Updated Successfully..');
            return $this->redirect($this->generateUrl('wit_admin_usergroup_index'));
        }else{

            echo "form not valid";

            echo "<pre>";
            var_dump($request->request->all());
            echo "</pre>";

            //$editForm->submit($request);
            echo $editForm->getErrorsAsString();

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
        if($id){

            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('ModelBundle:UserGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Deleted Successfully..');
            return $this->redirect($this->generateUrl('wit_admin_usergroup_index'));
        }
    }

    /**
     * this action will be used by users listing page to add users into selected group
     *
     * @Route("/addusers")
     * @Template()
     */
    public function addusersAction(Request $request)
    {
        //check if form was submitted
        if ($request->isMethod('POST')){

            //dump($this->get('request')->request->get('users'));
            //exit;

            $em = $this->getDoctrine()->getManager();

            $userGroupEntity = $em->getRepository('ModelBundle:UserGroup')->find( $this->get('request')->request->get('user_group') );
            
            /*
             * Iterate through users array, selected by user..
             * 
             */
            foreach ($this->get('request')->request->get('users') as $userid) {

                $userEntity = $em->getRepository('ModelBundle:User')->find($userid);

                $groupUser = new GroupUser();

                $groupUser->setUserGroup($userGroupEntity);
                $groupUser->setUser($userEntity);

                $em->persist($groupUser);
            }

            $em->flush();

            /*
             * record was created..
             * Redirect user
             */
            $this->get('session')->getFlashBag()->set('error', 'Selected User(s) were added in group successfully..');
            return $this->redirect($this->generateUrl('wit_admin_user_index'));
        }
    }

    /**
     * 
     * This action will be used by user groups page to allow user to browse users into a group
     * 
     * @Route("/browse-users/in/{group_id}")
     * @Method("GET")
     * @Template()
     */
    public function browseUsersByGroupAction($group_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:GroupUser')->findBy(array('userGroup'=>$group_id));

        if (!$entity) {
            //redirect user back if nothing is in this group
            $this->get('session')->getFlashBag()->set('error', 'This is an empty group!');
            return $this->redirect($this->generateUrl('wit_admin_usergroup_index'));
            //throw $this->createNotFoundException('Unable to find ApplicationGroup entity.');
        }

        return array(
            'groupedUsers' => $entity,
        );
    }

    /**
     * Deletes user from a group
     *
     * @Route("/delete/{id}/group-to-return-to/{group_id}")
     * @Method("GET")
     */
    public function deleteUserFromGroupAction($id, $group_id)
    {
        if($id){

            $em = $this->getDoctrine()->getManager();

            //$entity = $em->getRepository('ModelBundle:GroupUser')->find($id);

            $groupUserEntity = $em->createQuery("SELECT a FROM ModelBundle:GroupUser a WHERE a.userGroup=".$group_id." AND a.user=".$id)->setMaxResults(1)->getOneOrNullResult();

            if (!$groupUserEntity) {
                throw $this->createNotFoundException('Unable to find Group User entity.');
            }

            $em->remove($groupUserEntity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Deleted Successfully..');
            return $this->redirect($this->generateUrl('wit_admin_usergroup_browseusersbygroup', array('group_id'=>$group_id)));
        }
    }

}