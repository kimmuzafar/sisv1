<?php

namespace Wit\SADBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wit\ModelBundle\Entity\AdmissionScheduler;
use Wit\SADBundle\Form\AdmissionSchedulerType;

/**
 * AdmissionScheduler controller.
 *
 * @Route("/admissionscheduler")
 */
class AdmissionSchedulerController extends Controller
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
        $entities = $em->getRepository('ModelBundle:AdmissionScheduler')->findBy(array(), array('id' => 'DESC'));

        //form to create
        $addform = $this->createCreateForm(new AdmissionScheduler());

        //if user wants to edit a record
        if($id){
            $editEntity = $em->getRepository('ModelBundle:AdmissionScheduler')->find($id);
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
     * @param AdmissionScheduler $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdmissionScheduler $entity)
    {
        $form = $this->createForm(new AdmissionSchedulerType(), $entity, array(
            'action' => $this->generateUrl('wit_sad_admissionscheduler_new'),
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

            $entity = new AdmissionScheduler();
            
            $entity->setCreatedByName($this->get('security.context')->getToken()->getUser()->getFirstname());
            $entity->setDateCreated(new \DateTime());

            //addform to create an entity.
            $addform = $this->createCreateForm($entity);

            $addform->handleRequest($request);

            if ($addform->isValid()) {
                /*
                    generating new pattern for reference #
                    -R-1 being appended to user added reference number, that will be incremented by application submission.
                    example FALL-2015-R-1, FALL-2015-R-2, FALL-2015-R-3, FALL-2015-R-4
                    reference number should not be modified by user once created, specially "-R-1" part of it
                */
                $userGivenRefPattern = $addform->getData()->getApplicationsRefNo();
                $newReferencePattern = $userGivenRefPattern.'-R-0';
                $entity->setApplicationsRefNo($newReferencePattern);

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                /*
                 * record was created..
                 * Redirect user
                 */
                $this->get('session')->getFlashBag()->set('error', 'New record was added successfully..');
                return $this->redirect($this->generateUrl('wit_sad_admissionscheduler_index'));
            }

        }
    }

    /**
     * Creates a form to edit an entity.
     *
     * @param AdmissionScheduler $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(AdmissionScheduler $entity)
    {
        $form = $this->createForm(new AdmissionSchedulerType(), $entity, array(
            'action' => $this->generateUrl('wit_sad_admissionscheduler_update', array('id' => $entity->getId())),
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

        $entity = $em->getRepository('ModelBundle:AdmissionScheduler')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Updated Successfully..');
            return $this->redirect($this->generateUrl('wit_sad_admissionscheduler_index'));
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

            $entity = $em->getRepository('ModelBundle:AdmissionScheduler')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Deleted Successfully..');
            return $this->redirect($this->generateUrl('wit_sad_admissionscheduler_index'));
        }
    }
}
