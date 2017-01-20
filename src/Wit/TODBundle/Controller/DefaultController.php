<?php

namespace Wit\TODBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
        //to populate user notification logs in user dashboard right side block
        // if ( $this->get('security.context')->getToken()->getUser()->getLogs() ){
        //     $userLogs = $this->get('security.context')->getToken()->getUser()->getLogs();
        // }else{
        //     $userLogs = null;
        // }

        // return array(
        //     'userLogs' => $userLogs,
        // );

        $em = $this->getDoctrine()->getManager();
        $sadLogs = $em->getRepository('ModelBundle:UserLogs')->findBy(array('isForTod' => 1), array('id'=>'DESC'), 10);
        if (!$sadLogs) {
            //throw $this->createNotFoundException('Unable to find User Logs entity.');
            $sadLogs = null;
        }

        $onlineUserRole = $this->get('security.context')->getToken()->getUser()->getRoles()[0];
        $adminQuickNews = $em->getRepository('ModelBundle:QuickNews')->findBy(array('toUserRole'=>trim($onlineUserRole)), array('id'=>'DESC'), 5);

        return array(
            'sadLogs'   => $sadLogs,
            'quickNews' => $adminQuickNews,
        );
    }
}
