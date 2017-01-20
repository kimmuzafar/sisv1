<?php

namespace Wit\SADBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;

use Wit\ModelBundle\Entity\ApplicationGroup;
use Wit\ModelBundle\Entity\GroupApplication;

/**
 * Grouped Application controller.
 *
 * @Route("/grouped-application")
 */
class GroupedApplicationController extends Controller
{
    /**
     * This action will serve as sole action for Add/ Edit and Delete record
     * 
     * @Route("/")
     * 
     * @Template()
     */
    public function indexAction()
    {
        exit;
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

            $em = $this->getDoctrine()->getManager();

            $applicationGroupEntity = $em->getRepository('ModelBundle:ApplicationGroup')->find( $this->get('request')->request->get('application_group') );
            
            /*
             * Iterate through each application selected by user..
             * 
             */
            foreach ($this->get('request')->request->get('applications') as $appid) {

                $applicationEntity = $em->getRepository('ModelBundle:Application')->find($appid);

                $groupApp = new GroupApplication();

                $groupApp->setApplicationGroup($applicationGroupEntity);
                $groupApp->setApplication($applicationEntity);

                $em->persist($groupApp);
            }

            $em->flush();


            /*
             * record was created..
             * Redirect user
             */
            $this->get('session')->getFlashBag()->set('error', 'Selected Application were added in group successfully..');
            return $this->redirect($this->generateUrl('wit_sad_application_index'));
        }
    }

    /**
     * @Route("/browse/{id}")
     * @Method("GET")
     * @Template()
     */
    public function browseByGroupAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:GroupApplication')->findBy(array('applicationGroup'=>$id));

        if (!$entity) {
            //redirect user back if nothing is in this group
            $this->get('session')->getFlashBag()->set('error', 'This is an empty group!');
            return $this->redirect($this->generateUrl('wit_sad_applicationgroup_index'));
            //throw $this->createNotFoundException('Unable to find ApplicationGroup entity.');
        }

        return array(
            'groupedApplications' => $entity,
        );
    }
    
    /**
     * Deletes a entity.
     *
     * @Route("/delete/{id}/group-to-return-to/{group_id}")
     * @Method("GET")
     */
    public function deleteAction($id, $group_id)
    {
        if($id){

            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('ModelBundle:GroupApplication')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Deleted Successfully..');
            return $this->redirect($this->generateUrl('wit_sad_groupedapplication_browsebygroup', array('id'=>$group_id)));
        }
    }
}