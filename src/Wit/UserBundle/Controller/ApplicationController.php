<?php

namespace Wit\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wit\ModelBundle\Entity\Application;
use Wit\UserBundle\Form\ApplicationType;

use Wit\ModelBundle\Entity\UserLogs;
use Wit\ModelBundle\Entity\AdmissionScheduler;

/**
 * Application controller.
 *
 * @Route("/application")
 */
class ApplicationController extends Controller
{
    /**
     * Lists all Application entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userApplicationInCurrentSchedule = $em->getRepository('ModelBundle:User')->findUserApplicationInCurrentSchedule($this->get('wit.helper.user')->getOnlineUser());

        if ( $userApplicationInCurrentSchedule == "submit" ){
            # user can submit application
            //echo "You can submit an application";

            // return array(
            //     'application' => null,
            // );

            return $this->redirect($this->generateUrl('wit_user_application_new'));

        }else if ( $userApplicationInCurrentSchedule == "closed" ){
            # admission is closed
            return $this->render('UserBundle:Application:admissionclosed.html.twig');
        }else{
            # return application object here, as user already has an application
            //echo "You already have an application in this schedule..";

            // return array(
            //     'application' => $userApplicationInCurrentSchedule,
            // );

            return $this->redirect($this->generateUrl('wit_user_application_status', array('application_id' => $userApplicationInCurrentSchedule->getId())));
        }
    }

    /**
     * Creates a new Application entity.
     *
     * @Route("/")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Application();

        /*
         * Setting non form data for the entity
         */
        $entity->setDateCreated(new \DateTime());
        $entity->setApplicationStatus(0); // set application status to "0 = pending"
        //$entity->setApplicationReferenceNo(date("2015")."-".rand(3,99)."-".rand(3,99999)); //setting application reference number..

        //getting online user instance for $entity->setUser()
        $onlineUserInstance = $em->getRepository('ModelBundle:User')->find($this->get('security.context')->getToken()->getUser()->getId());
        $entity->setUser($onlineUserInstance); //setting user

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        //echo $form->all();
        //exit;

        if ($form->isValid()) {

             //getting form data to be later
            // $formData = $form->getData();
            // $email = $formData->getEmail();
            // $mobile = $formData->getMobile();

            //getting reference no pattern from scheduler
            $scheduler = $form->getData()->getAdmissionScheduler()->getApplicationsRefNo();

            //getting scheduler instance to update reference number each time new application is submitted..
            $schedulerInstance = $em->getRepository('ModelBundle:AdmissionScheduler')->find($form->getData()->getAdmissionScheduler()->getId());

            //checking if -R- part already exist in reference no
            if (strpos($scheduler,'-R-') !== false) {
                
                //it already given do number increment below

                $schedulerRefParts = explode("-R-", $scheduler);

                $refLeftPart = $schedulerRefParts[0]; //static part
                $refRightPart = $schedulerRefParts[1]; //this is the number that will be incremented on each application submission

                $newReferenceNo = $refLeftPart."-R-".($refRightPart+1);

            }else{

                //it does't exist
                //this else block will execute if "-R-" does't exist in Admission Scheduler set reference no.

                $newReferenceNo = $scheduler."-R-1";
            }

            $schedulerInstance->setApplicationsRefNo($newReferenceNo); //updating scheduler reference number

            //setting new reference no.
            $entity->setApplicationReferenceNo($newReferenceNo); //setting user

            $em->persist($entity);
            $em->flush();

            /*
             * Sending application submitted email
             *
             * */

            //validate email address and send email
            if (!filter_var($entity->getEmail(), FILTER_VALIDATE_EMAIL) === false) {
                
                $fullname = $entity->getFirstname().' '.$entity->getFathersName().' '.$entity->getGrandFathersName().' '.$entity->getFamilyName();

                $subject = "Ref#: ".$entity->getApplicationReferenceNo()." - Your Application submission receipt, at (WIT)";

                $messageBody = "<strong>Dear ".$fullname.',</strong><br />';
                
                $messageBody .= "<br />With respect to above subject, this is to let you know that your application has been successfully submitted at http://wit.edu.sa, we will be reviewing it shortly and you will be notified about application status, please keep following details for you reference:";
                
                $messageBody .= "<p><strong>REFERENCE #:</strong> <br />".$entity->getApplicationReferenceNo()."<br /><br />";
                $messageBody .= "<strong>FULL NAME:</strong> <br />".$fullname."<br /><br />";
                $messageBody .= "<strong>NATIONAL ID:</strong> <br />".$entity->getNationalId()."<br /><br />";
                $messageBody .= "<strong>EMAIL:</strong> <br />".$entity->getEmail()."<br /><br />";
                $messageBody .= "<strong>MOBILE #:</strong> <br />".$entity->getMobile()."</p><br />";

                $messageBody .= "Please feel free to email us at 'support@wit.edu.sa' to post any feedback/ suggestion or to get our support.<br /><br />";
                
                $messageBody .= "<strong>Best Regards,</strong><br />";
                $messageBody .= "Al Watania Poultry Institute Of Technology (WIT)<br />";
                $messageBody .= "P.O.Box: 111, Al Bukayriah 51941<br />Al Qassim, K.S.A";

                $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom('noreply@wit.edu.sa')
                    ->setTo(array($entity->getEmail() => $fullname))
                    ->setBody($messageBody,'text/html');

                $this->get('mailer')->send($message);

            } else {
                //not valid email address
            }

            /*
             * Sending SMS to candidates mobile number
             *
             * Check if mobile number is not empty, check if it has 9665 in start, check if it's length is 12 digits
             *
             * */
            $mobileNumber = trim($entity->getMobile());
            if (!empty($mobileNumber) AND substr($mobileNumber, 0, 4)==="9665" AND strlen($mobileNumber)===12) {
                
                //mobile number is valid so send SMS now..

                $url = "http://brazilboxtech.com/api/send.aspx?";
                $queryString = http_build_query(array(
                    'username'=>'ishaman',
                    'password'=>'witsmsksa5917',
                    'language'=>1,
                    'sender'=>'WIT',
                    'mobile'=>'966599218825',
                    'message'=>'Thank You, Your application was submitted, following is your reference #:'.trim($entity->getApplicationReferenceNo())
                ));
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $url.$queryString);
                curl_exec($ch);
                curl_close ($ch);
            }

            //application completion log is being created by applicationListener

            $this->get('session')->getFlashBag()->set('error', 'Your Application was submitted successfully..');
            return $this->redirect($this->generateUrl('wit_user_application_index'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Application entity.
     *
     * @param Application $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Application $entity)
    {
        $form = $this->createForm(new ApplicationType(), $entity, array(
            'action' => $this->generateUrl('wit_user_application_create'),
            'method' => 'POST',
            'attr'	 => array(
        		'id'=>'form-wizard',
        	),
        ));

        // $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Application entity.
     *
     * @Route("/new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userApplicationInCurrentSchedule = $em->getRepository('ModelBundle:User')->findUserApplicationInCurrentSchedule($this->get('wit.helper.user')->getOnlineUser());

        if ( $userApplicationInCurrentSchedule == "submit" ){
            # user can submit application
            //echo "You can submit an application";

            $entity = new Application();
            $form   = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form'   => $form->createView(),
            );

        }else if ( $userApplicationInCurrentSchedule == "closed" ){
            # admission is closed
            return $this->redirect($this->generateUrl('wit_user_application_index'));
        }else{
            # return application object here, as user already has an application
            //echo "You already have an application in this schedule..";
            return $this->redirect($this->generateUrl('wit_user_application_index'));
        }
    }

    /**
     * Finds and displays a Application entity.
     *
     * @Route("/{application_id}/view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction($application_id)
    {
        $em = $this->getDoctrine()->getManager();

        $applicationEntity = $em->getRepository('ModelBundle:Application')->find($application_id);

        if (!$applicationEntity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        return array(
            'application' => $applicationEntity,
        );
    }

    /**
     * Displays a form to edit an existing Application entity.
     *
     * @Route("/{id}/edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        //security: if application editing was not allowed by SAD user cannot edit it
        if($entity->getIsEditable()==0){
            $this->get('session')->getFlashBag()->set('red-error', 'You are not allowed to edit your application!');
            return $this->redirect($this->generateUrl('wit_user_application_index'));
            exit;
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Application entity.
    *
    * @param Application $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Application $entity)
    {
        $form = $this->createForm(new ApplicationType(), $entity, array(
            'action' => $this->generateUrl('wit_user_application_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr'   => array(
                'id'=>'form-wizard',
            ),
        ));

        // $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Application entity.
     *
     * @Route("/{id}")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        $entity->setIsEditable(0); //turn off editing as it's just one time editing once enabled by SAD

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Your application was updated successfully..');
            return $this->redirect($this->generateUrl('wit_user_application_index'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Application entity.
     *
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ModelBundle:Application')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Application entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('application'));
    }

    /**
     * Creates a form to delete a Application entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('wit_user_application_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Displays a page for user with their application history
     *
     * @Route("/history")
     * @Method("GET")
     * @Template()
     */
    public function historyAction()
    {
        $em = $this->getDoctrine()->getManager();
        $applications = $em->getRepository('ModelBundle:User')->findUserApplications($this->get('wit.helper.user')->getOnlineUser());

        return array(
            'applications' => $applications,
        );
    }

    /**
     * Displays a page for registrant application status
     *
     * @Route("/{application_id}/status/")
     * @Method("GET")
     * @Template()
     */
    public function statusAction($application_id)
    {
        $em = $this->getDoctrine()->getManager();
        $applicationEntity = $em->getRepository('ModelBundle:Application')->find($application_id);

        return array(
            'application' => $applicationEntity,
        );
    }
}