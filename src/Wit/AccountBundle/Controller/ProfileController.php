<?php

namespace Wit\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use Wit\ModelBundle\Entity\Role;
use Wit\ModelBundle\Entity\User;

use Wit\AccountBundle\Form\UserType;

/**
 * Profile controller.
 *
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * This action will serve as sole action for view/ Edit User
     *
     * @Route("/")
     * @Route("/edit/{flag}", defaults={"flag" = null})
     * 
     * @Template()
     */
    public function indexAction($flag=null)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('ModelBundle:User')->find($this->get('security.context')->getToken()->getUser()->getId());

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        //if user wants to edit
        if($flag){
            $editUserForm = $this->createForm(new UserType(), $user, array(
                'action' => $this->generateUrl('wit_account_profile_update'),
                'method' => 'PUT',
                'attr'   => array(
                    'id' => 'update-user-form',
                    'novalidate' => 'novalidate',
                )
            ))->remove('password')->remove('roles')->remove('email')->remove('nationalid'); //this will remove password and roles fields from form before submitting

            return array(
                'user'      => $user,
                'application'   => $user->getApplications(),
                'editUserForm'  => $editUserForm->createView(),
            );
        }else{
            return array(
                'user'      => $user,
                'application'   => $user->getApplications(),
            );
        }
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/update")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request)
    {
        $id = $this->get('security.context')->getToken()->getUser()->getId();

        $em = $this->getDoctrine()->getManager();

        $userEntity = $em->getRepository('ModelBundle:User')->find($id);

        if (!$userEntity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $userEntity, array(
            'action' => $this->generateUrl('wit_account_profile_update'),
            'method' => 'PUT',
            'attr' => array(
                'id' => 'update-user-form',
                'novalidate' => 'novalidate',
            )
        ))
        //remove below fields from form before submitting it to entity
        ->remove('password')
        ->remove('roles')
        ->remove('email')
        ->remove('nationalid')
        ->remove('isActive');

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Your Profile info was updated successfully..');
            return $this->redirect($this->generateUrl('wit_account_profile_index'));
        }else{

            // echo "form not valid";

            // echo "<pre>";
            // var_dump($request->request->all());
            // echo "</pre>";

            var_dump($editForm->getErrors());

            //$editForm->submit($request);
            var_dump($editForm->getErrorsAsString());

            exit;
        }
    }
}