<?php

namespace Wit\StudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Wit\ModelBundle\Entity\Enrolment;

/**
 * Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {	
    	/*
			TODO: i need to check if any logs need to be displayed for student as well... 
    	*/
        //to populate user notification logs in user dashboard right side block
        /*
        if ( $this->get('security.context')->getToken()->getUser()->getLogs() ){
            $userLogs = $this->get('security.context')->getToken()->getUser()->getLogs();
        }else{
            $userLogs = null;
        }
        */

        /* TODO: i need to display site news in students dashboard */

        /* TODO: I need to display all coureses current student is enrolled in, in student dashboard */

        $em = $this->getDoctrine()->getManager();

        $sadLogs = $em->getRepository('ModelBundle:UserLogs')->findBy(array('isForStudent' => 1), array('id'=>'DESC'), 10);
        if (!$sadLogs) {
            //throw $this->createNotFoundException('Unable to find User Logs entity.');
            $sadLogs = null;
        }

        //Getting online user instance
        $user = $em->getRepository('ModelBundle:User')->find($this->get('security.context')->getToken()->getUser()->getId());
        //Getting all enrolments against online user
        $enrolmentData = $em->getRepository('ModelBundle:Enrolment')->findBy(array('user'=>$user), array('id' => 'DESC'));

        $onlineUserRole = $this->get('security.context')->getToken()->getUser()->getRoles()[0];
        $adminQuickNews = $em->getRepository('ModelBundle:QuickNews')->findBy(array('toUserRole'=>trim($onlineUserRole)), array('id'=>'DESC'), 5);

        return array(
            'userLogs' => $sadLogs,
            'enrolmentData' => $enrolmentData,
            'quickNews' => $adminQuickNews,
        );
    }
}
