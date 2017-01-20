<?php

namespace Wit\CourseManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;

use Wit\ModelBundle\Entity\CourseQuarter;
use Wit\CourseManagementBundle\Form\QuarterType;

use Wit\ModelBundle\Entity\Course;

/**
 * Coursequarter controller.
 *
 * @Route("/quarter")
 */
class QuarterController extends Controller
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
        $entities = $em->getRepository('ModelBundle:CourseQuarter')->findBy(array(), array('id' => 'DESC'));

        //form to create
        $addform = $this->createCreateForm(new CourseQuarter());

        //if user wants to edit a record
        if($id){
            $editEntity = $em->getRepository('ModelBundle:CourseQuarter')->find($id);
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
     * @param CourseQuarter $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CourseQuarter $entity)
    {
        $form = $this->createForm(new QuarterType(), $entity, array(
            'action' => $this->generateUrl('wit_coursemanagement_quarter_new'),
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

            $entity = new CourseQuarter();

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
                return $this->redirect($this->generateUrl('wit_coursemanagement_quarter_index'));
            }

        }
    }

    /**
     * Creates a form to edit an entity.
     *
     * @param CourseQuarter $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CourseQuarter $entity)
    {
        $form = $this->createForm(new QuarterType(), $entity, array(
            'action' => $this->generateUrl('wit_coursemanagement_quarter_update', array('id' => $entity->getId())),
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

        $entity = $em->getRepository('ModelBundle:CourseQuarter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Updated Successfully..');
            return $this->redirect($this->generateUrl('wit_coursemanagement_quarter_index'));
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

            $entity = $em->getRepository('ModelBundle:CourseQuarter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Deleted Successfully..');
            return $this->redirect($this->generateUrl('wit_coursemanagement_quarter_index'));
        }
    }

    /**
     * set current quarter
     * only 1 quarter can be current quarter in a batch
     * Once a quarter is marked as current, current quarter status from previous current quarter will be removed in order to keep only one quarter as current in a batch
     *
     * Parameters:
     * id = quarter id
     *
     * @Route("/{id}/status")
     * @Method("GET")
     */
    public function statusAction($id)
    {
        if($id){

            $em = $this->getDoctrine()->getManager();

            $selectedQuarterEntity = $em->getRepository('ModelBundle:CourseQuarter')->find($id);
            if (!$selectedQuarterEntity) {
                throw $this->createNotFoundException('Unable to find quarter entity.');
            }

            //getting selected course batch
            $batchId = $selectedQuarterEntity->getCoursebatch()->getId();
            
            //getting previous current quarters having current quarter status in selected quarter batch
            //because multiple quarters and same batch by mistake can be marked as current so get all those and to reset them
            $previousCurrentQuarterEntities = $em
                ->createQuery("SELECT a FROM ModelBundle:CourseQuarter a WHERE a.isCurrentQuarter=1 AND a.coursebatch=:courseBatch")
                ->setParameter('courseBatch', $batchId)->getResult();

            foreach ($previousCurrentQuarterEntities as $quarter) {
                //echo $quarter->getCoursebatch()." - ".$quarter->getTitle()."<br />";

                //remove current quarter status from previous current quarter in selected quarter batch
                $quarter->setIsCurrentQuarter(0);
            }

            $em->flush();

            $selectedQuarterEntity->setIsCurrentQuarter(1);

            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Selected quarter status updated Successfully..');
            return $this->redirect($this->generateUrl('wit_coursemanagement_quarter_index'));
        }
    }

    /**
     * TODO: URL security from teachers side to be checked..
     *
     * Allow or Cancel grade submission for all courses in selected quarter
     *
     * Parameters:
     * id: quarter id
     * status: 0 = cancel grade submission, 1 = allow grade submission
     *
     * @Route("/{quarter_id}/grading-submission-status/{status}")
     * @Method("GET")
     */
    public function gradeSubmissionStatusAction($quarter_id, $status)
    {
        if(isset($quarter_id) AND isset($status) ){

            $em = $this->getDoctrine()->getManager();

            $quarterEntity = $em->getRepository('ModelBundle:CourseQuarter')->find($quarter_id);

            if (!$quarterEntity) {
                throw $this->createNotFoundException('Unable to find quarter entity.');
            }

            $courseEntities = $em->createQuery("SELECT c.id FROM ModelBundle:Course c WHERE c.coursequarter=:quarter_id ORDER BY c.id DESC")->setParameter("quarter_id", $quarter_id)->getResult();

            foreach ($courseEntities as $course) {

                $courseEntity = $em->getRepository('ModelBundle:Course')->find($course['id']);

                //set each course grading status in selected quarter
                $courseEntity->setIsAllowedGrading(trim($status));
            }

            //set quarter grading status as well
            $quarterEntity->setIsAllowedGrading(trim($status));
            
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Grade submission status was updated for all courses in selected quarter..');
            return $this->redirect($this->generateUrl('wit_coursemanagement_quarter_index'));
        }
    }

}