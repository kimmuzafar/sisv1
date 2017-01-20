<?php

namespace Wit\QuickNewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;

use Wit\ModelBundle\Entity\QuickNews;
use Wit\QuickNewsBundle\Form\QuickNewsType;
use Wit\ModelBundle\Entity\UserLogs;

/**
 * Quick News Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * This action will allow user to send news
     * 
     * @Route("/send")
     * 
     * @Template()
     */
    public function indexAction()
    {
        //form to create
        $addform = $this->createCreateForm(new QuickNews());

        return array(
            'addForm'       => $addform->createView(),
        );
    }

    /**
     * Creates a form to create an entity.
     *
     * @param QuickNews $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(QuickNews $entity)
    {
        $form = $this->createForm(new QuickNewsType(), $entity, array(
            'action' => $this->generateUrl('wit_quicknews_default_new'),
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


            $onlineUserId = $this->get('security.context')->getToken()->getUser()->getId();
            $userFullName = $this->get('security.context')->getToken()->getUser()->getFirstname()." ".$this->get('security.context')->getToken()->getUser()->getLastname();

            $entity = new QuickNews();
            $entity->setFromUserName($userFullName);
            $entity->setDateCreated(new \DateTime()); // updating date created..
            $entity->setIsRead(0); // updating date created..

            //addform to create an entity.
            $addform = $this->createCreateForm($entity);

            $addform->handleRequest($request);

            if ($addform->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                //getting user role from user submited form
                $selectedUserRole = $addform->getData()->getToUserRole();

                /*
                 * Quick news was submitted..
                 * Create log for the same
                 */
                $userLog = new UserLogs();
                $userLog->setDetail("Left a quick message for you! <a href='".$this->generateUrl('wit_quicknews_default_view', array('id'=>$entity->getId()))."'>Read More..</a>");
                $userLog->setGenerator($userFullName);
                $userLog->setDateCreated(new \DateTime());
                $userLog->setIsActive(1);

                $userLog->setFromUserId($onlineUserId); //this will help in figuring out who created this log
                //$userLog->setForUserId(); //this is the user to whom this log will display
                if ($selectedUserRole=="ROLE_DH"){
                    $userLog->setIsForDh(1); //if 1 it will display in DH notifications
                }else if ($selectedUserRole=="ROLE_ADMIN"){
                    
                }else if ($selectedUserRole=="ROLE_TOD"){
                    $userLog->setIsForTod(1); //if 1 it will display in tod notifications
                }else if ($selectedUserRole=="ROLE_SAD"){
                    $userLog->setIsForSad(1); //if 1 it will display in sad notifications
                }else if ($selectedUserRole=="ROLE_USER"){
                    $userLog->setIsForUser(1); //if 1 it will display for all users registered.
                }else if ($selectedUserRole=="ROLE_TEACHER"){
                    $userLog->setIsForTeacher(1); //if 1 it will display in Teachers notifications
                }else if ($selectedUserRole=="ROLE_STUDENT"){
                    $userLog->setIsForStudent(1); //if 1 it will display in Student's notifications
                }

                $em->persist($userLog);
                $em->flush();

                /*
                 * record was created..
                 * Redirect user
                 */
                $this->get('session')->getFlashBag()->set('error', 'Quick New Sent Successfully..');
                return $this->redirect($this->generateUrl('wit_quicknews_default_index'));
            }

        }
    }

    /**
     * @Route("/{id}/view/")
     * @Template()
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:QuickNews')->find($id);
        $entity->setIsRead(1);
        
        $em->flush();

        return array(
            'quickNews' => $entity,
        );
    }

    /**
     * @Route("/view/all")
     * 
     * This action will display all news send to current online user..
     * 
     * @Template()
     */
    public function viewAllAction()
    {
        $em = $this->getDoctrine()->getManager();

        $onlineUserRole = trim($this->get('security.context')->getToken()->getUser()->getRoles()[0]);
        $entities = $em->getRepository('ModelBundle:QuickNews')->findBy(array('toUserRole'=>$onlineUserRole), array('id'=>'DESC'));

        return array(
            'entities' => $entities,
        );
    }
}