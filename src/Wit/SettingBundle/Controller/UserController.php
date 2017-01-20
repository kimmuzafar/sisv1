<?php

namespace Wit\SettingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use Wit\ModelBundle\Entity\Role;
use Wit\SettingBundle\Form\RoleType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('ModelBundle:Role')->findBy(array(), array('id' => 'DESC'));
        
        //form to create a Role
        $addUserRoleform = $this->createForm(new RoleType(), new Role(), array(
            'action' => $this->generateUrl('wit_setting_user_rolenew'),
            'method' => 'POST',
            'attr' => array(
                'id'    => 'add-new-role-form',
            )
        ));

        return array(
            'roles' => $roles,
            'addUserRoleForm' => $addUserRoleform->createView(),
        );
    }

    /**
     * Create a new role entity.
     *
     * @Route("/role/new")
     * @Template()
     */
    public function roleNewAction(Request $request)
    {
        $roleEntity = new Role();

        //form to create a Role entity.
        $form = $this->createForm(new RoleType(), $roleEntity, array(
            'action' => $this->generateUrl('wit_setting_user_rolenew'),
            'method' => 'POST',
            'attr' => array(
                'id'    => 'add-new-role-form',
            )
        ));

        //check if form was submitted
        if ($request->isMethod('POST')){

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($roleEntity);
                $em->flush();

                /*
                 * Role was created..
                 * Redirect user to add role page
                 */
                $this->get('session')->getFlashBag()->set('error', 'Role was added successfully..');
                return $this->redirect($this->generateUrl('wit_setting_user_index'));
            }

        }

        return array(
            'roleEntity' => $roleEntity,
            'form'   => $form->createView(),
        );
    }
}
