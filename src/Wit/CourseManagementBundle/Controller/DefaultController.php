<?php

namespace Wit\CourseManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;

use Wit\ModelBundle\Entity\Course;
use Wit\CourseManagementBundle\Form\CourseType;

/**
 * Course controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
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
        $entities = $em->getRepository('ModelBundle:Course')->findBy(array(), array('id' => 'DESC'));

        //form to create
        $addform = $this->createCreateForm(new Course());

        //if user wants to edit a record
        if($id){
            $editEntity = $em->getRepository('ModelBundle:Course')->find($id);
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
     * @param Course $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Course $entity)
    {
        $form = $this->createForm(new CourseType(), $entity, array(
            'action' => $this->generateUrl('wit_coursemanagement_default_new'),
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

            $entity = new Course();

            $entity->setDateCreated(new \DateTime());

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
                return $this->redirect($this->generateUrl('wit_coursemanagement_default_index'));
            }

        }
    }

    /**
     * Creates a form to edit an entity.
     *
     * @param Course $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Course $entity)
    {
        $form = $this->createForm(new CourseType(), $entity, array(
            'action' => $this->generateUrl('wit_coursemanagement_default_update', array('id' => $entity->getId())),
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

        $entity = $em->getRepository('ModelBundle:Course')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Updated Successfully..');
            return $this->redirect($this->generateUrl('wit_coursemanagement_default_index'));
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

            $entity = $em->getRepository('ModelBundle:Course')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Deleted Successfully..');
            return $this->redirect($this->generateUrl('wit_coursemanagement_default_index'));
        }
    }

    /**
     * TODO: URL security from teachers side to be checked..
     *
     * Allow or Cancel grade submission for selected course
     *
     * Parameters:
     * id: course id
     * status: 0 = cancel grade submission, 1 = allow grade submission
     *
     * @Route("/{id}/grading-submission-status/{status}")
     * @Method("GET")
     */
    public function gradeSubmissionStatusAction($id, $status)
    {
        if(isset($id) AND isset($status) ){

            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('ModelBundle:Course')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find course entity.');
            }

            $entity->setIsAllowedGrading(trim($status));
            
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Grade submission status was changed for selected course..');
            return $this->redirect($this->generateUrl('wit_coursemanagement_default_index'));
        }
    }

}