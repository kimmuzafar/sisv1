<?php

namespace Wit\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Wit\ModelBundle\Entity\UserLogs;
use Wit\ModelBundle\Entity\QuickNews;

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
        $em = $this->getDoctrine()->getManager();

        $adminLogs = $em->getRepository('ModelBundle:UserLogs')->findBy(array(), array('id'=>'DESC'), 10);
        if (!$adminLogs) {
            //throw $this->createNotFoundException('Unable to find User Logs entity.');
            $adminLogs = null;
        }

        $onlineUserRole = $this->get('security.context')->getToken()->getUser()->getRoles()[0];
        $adminQuickNews = $em->getRepository('ModelBundle:QuickNews')->findBy(array('toUserRole'=>trim($onlineUserRole)), array('id'=>'DESC'), 5);

        return array(
            'adminLogs'   => $adminLogs,
            'quickNews' => $adminQuickNews,
        );
    }
}
