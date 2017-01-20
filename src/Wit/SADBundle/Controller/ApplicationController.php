<?php

namespace Wit\SADBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use Wit\ModelBundle\Entity\Application;
use Wit\SADBundle\Form\SearchApplicationType;

use Doctrine\Common\Collections\Criteria;

/**
 * Application controller.
 *
 * @Route("/application")
 */
class ApplicationController extends Controller
{
    /**
     * @Route("/")
     * //Below route will be called by each Admission Scheduler to diplay it's specific applications.. 
     * @Route("/admission-scheduler/{id}", defaults={"id" = null})
     * @Template()
     */
    public function indexAction($id=null)
    {
    	$em = $this->getDoctrine()->getManager();
        
        //check if id is available in url so user want to see applications based on that id
        if ($id){
            //fetch applications be admission scheduler id
            $applications = $em->getRepository('ModelBundle:Application')->findBy(array('admissionScheduler'=>$id), array('id' => 'DESC'));
        }else{
            $applications = $em->getRepository('ModelBundle:Application')->findBy(array(), array('id' => 'DESC'));
        }
        
        //to populate application gorup creation dropdown
        $applicationGroups = $em->getRepository('ModelBundle:ApplicationGroup')->findBy(array(), array('id' => 'DESC'));
        
        //side filter/search form
        if ($id){
            $searchForm = $this->createSearchFormWithAdmissionSchedule($id);
            #/sad/application/search?admissionScheduleId=3 
        }else{
            $searchForm = $this->createSearchForm();
        }

        return array(
        	'applications' => $applications,
            'searchform'   => $searchForm->createView(),
            'applicationGroups' => $applicationGroups,
        );
    }

    /**
     * Creates a form for search applications
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm()
    {
        $form = $this->createForm(new SearchApplicationType(), new Application(), array(
            'action' => $this->generateUrl('wit_sad_application_search', array('admissionScheduleId' => 0)),
            'method' => 'POST',
            'attr'   => array(
                'novalidate' => 'novalidate',
                'id' => 'search_application_form',
            ),
        ));

        return $form;
    }
    
    /**
     * Creates a form for search applications if application page was filtered by admission scheduler
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchFormWithAdmissionSchedule($admissionScheduleId)
    {
        $form = $this->createForm(new SearchApplicationType(), new Application(), array(
            'action' => $this->generateUrl('wit_sad_application_search', array('admissionScheduleId' => $admissionScheduleId)),
            'method' => 'POST',
            'attr'   => array(
                'novalidate' => 'novalidate',
                'id' => 'search_application_form',
            ),
        ));

        return $form;
    }
    
    /**
     * @Route("/search/with/admission-schedule/{admissionScheduleId}")
     * @Template()
     */
    public function searchAction($admissionScheduleId=null, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $applicationGroups = $em->getRepository('ModelBundle:ApplicationGroup')->findBy(array(), array('id' => 'DESC'));

        if ($request->isMethod('POST')){

            /*
             * Getting submitted fields through filter/search form
             * And getting instance of respective entity for each submitted field as required
             */
            $maritalStatus      = $em->getRepository("ModelBundle:MaritalStatus")->find($this->get('request')->request->get('wit_modelbundle_application')['maritalStatus']);
            $gender             = $em->getRepository("ModelBundle:Gender")->find($this->get('request')->request->get('wit_modelbundle_application')['gender']);
            $city               = $em->getRepository("ModelBundle:City")->find($this->get('request')->request->get('wit_modelbundle_application')['city']);
            $country            = $em->getRepository("ModelBundle:Country")->find($this->get('request')->request->get('wit_modelbundle_application')['country']);
            $permanentCity      = $em->getRepository("ModelBundle:City")->find($this->get('request')->request->get('wit_modelbundle_application')['permanentCity']);
            $permanentCountry   = $em->getRepository("ModelBundle:Country")->find($this->get('request')->request->get('wit_modelbundle_application')['permanentCountry']);
            $studyLevel         = $em->getRepository("ModelBundle:StudyLevel")->find($this->get('request')->request->get('wit_modelbundle_application')['studyLevel']);
            $completionDate     = $em->getRepository("ModelBundle:CompletionDate")->find($this->get('request')->request->get('wit_modelbundle_application')['completionDate']);
            $completionCity     = $em->getRepository("ModelBundle:City")->find($this->get('request')->request->get('wit_modelbundle_application')['completionCity']);
            $grade              = $em->getRepository("ModelBundle:Grade")->find($this->get('request')->request->get('wit_modelbundle_application')['grade']);
            $accumulatedGrade   = $em->getRepository("ModelBundle:AccumulatedGrade")->find($this->get('request')->request->get('wit_modelbundle_application')['accumulatedGrade']);
            $qiyasGrade         = $em->getRepository("ModelBundle:QiyasGrade")->find($this->get('request')->request->get('wit_modelbundle_application')['qiyasGrade']);
            $englishGrade       = $em->getRepository("ModelBundle:EnglishGrade")->find($this->get('request')->request->get('wit_modelbundle_application')['englishGrade']);
            $major              = $em->getRepository("ModelBundle:Major")->find($this->get('request')->request->get('wit_modelbundle_application')['major']);
            $hearAboutUs        = $em->getRepository("ModelBundle:HearAboutUs")->find($this->get('request')->request->get('wit_modelbundle_application')['hearAboutUs']);
            
            /*
             * Getting repository for Application entity
             * And creating criteria for it
             */
            $applicationsRepository = $em->getRepository('ModelBundle:Application');
            
            $criteria = new Criteria();
            
            //check if user is searching after filtering applications by Admission Scheduler
            if( $admissionScheduleId > 0 ){
                $admissionScheduleInstance = $em->getRepository("ModelBundle:AdmissionScheduler")->find($admissionScheduleId);
                $criteria->andWhere($criteria->expr()->eq('admissionScheduler', $admissionScheduleInstance));
            }
            
            if($maritalStatus){
                $criteria->andWhere($criteria->expr()->eq('maritalStatus', $maritalStatus));
                //echo $maritalStatus."<br />";
            }
            if($gender){
                $criteria->andWhere($criteria->expr()->eq('gender', $gender));
                //echo $gender."<br />";
            }
            if($city){
                $criteria->andWhere($criteria->expr()->eq('city', $city));
                //echo $city."<br />";
            }
            if($country){
                $criteria->andWhere($criteria->expr()->eq('country', $country));
                //echo $country."<br />";
            }
            if($permanentCity){
                $criteria->andWhere($criteria->expr()->eq('permanentCity', $permanentCity));
                //echo $permanentCity."<br />";
            }
            if($permanentCountry){
                $criteria->andWhere($criteria->expr()->eq('permanentCountry', $permanentCountry));
                //echo $permanentCountry."<br />";
            }
            if($studyLevel){
                $criteria->andWhere($criteria->expr()->eq('studyLevel', $studyLevel));
                //echo $studyLevel."<br />";
            }
            if($completionDate){
                $criteria->andWhere($criteria->expr()->eq('completionDate', $completionDate));
                //echo $completionDate."<br />";
            }
            if($completionCity){
                $criteria->andWhere($criteria->expr()->eq('completionCity', $completionCity));
                //echo $completionCity."<br />";
            }
            if($grade){
                $criteria->andWhere($criteria->expr()->eq('grade', $grade));
                //echo $grade."<br />";
            }
            if($accumulatedGrade){
                $criteria->andWhere($criteria->expr()->eq('accumulatedGrade', $accumulatedGrade));
                //echo $accumulatedGrade."<br />";
            }
            if($qiyasGrade){
                $criteria->andWhere($criteria->expr()->eq('qiyasGrade', $qiyasGrade));
                //echo $qiyasGrade."<br />";
            }
            if($englishGrade){
                $criteria->andWhere($criteria->expr()->eq('englishGrade', $englishGrade));
                //echo $englishGrade."<br />";
            }
            if($major){
                $criteria->andWhere($criteria->expr()->eq('major', $major));
                //echo $major."<br />";
            }
            if($hearAboutUs){
                $criteria->andWhere($criteria->expr()->eq('hearAboutUs', $hearAboutUs));
                //echo $hearAboutUs."<br />";
            }
            
            $criteria->orderBy(array('id' => 'ASC'));

            $result = $applicationsRepository->matching($criteria);
            
            //because if user presses search button 2nd time it looses, Admission Scheduler id
            //in this case i need to persis it so user can press search button many times without loosing AdmissionScheduler id
            if( $admissionScheduleId > 0 ){
                $searchForm = $this->createSearchFormWithAdmissionSchedule($admissionScheduleId);
            }else{
                $searchForm = $this->createSearchForm();
            }
            
            /*
             * Populating search/filter form on this page 
             */
            $searchForm->get('maritalStatus')->setData($maritalStatus);
            $searchForm->get('gender')->setData($gender);
            $searchForm->get('city')->setData($city);
            $searchForm->get('country')->setData($country);
            $searchForm->get('permanentCity')->setData($permanentCity);
            $searchForm->get('permanentCountry')->setData($permanentCountry);
            $searchForm->get('studyLevel')->setData($studyLevel);
            $searchForm->get('completionDate')->setData($completionDate);
            $searchForm->get('completionCity')->setData($completionCity);
            $searchForm->get('grade')->setData($grade);
            $searchForm->get('accumulatedGrade')->setData($accumulatedGrade);
            $searchForm->get('qiyasGrade')->setData($qiyasGrade);
            $searchForm->get('englishGrade')->setData($englishGrade);
            $searchForm->get('major')->setData($major);
            $searchForm->get('hearAboutUs')->setData($hearAboutUs);

            return array(
                'searchResult' => $result,
                'searchform'   => $searchForm->createView(),
                'applicationGroups' => $applicationGroups,
            );

        }else{

            /*
             * Redirect user if came without search/filter form submission
             */
            $this->get('session')->getFlashBag()->set('error', 'No result was returned, Please use "Search/Filter Applications Form" ');
            return $this->redirect($this->generateUrl('wit_sad_application_index'));

        }//end: isPost check

    }//end searchAction

    /**
     * @Route("/view/{id}")
     * @Method("GET")
     * @Template()
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $applicationEntity = $em->getRepository('ModelBundle:Application')->find($id);

        if (!$applicationEntity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        return array(
            'application' => $applicationEntity,
        );
    }

    /**
     * update application status
     *
     * Parameters:
     * status (0=Pending, 1=Reviewed, 2=Accepted, 3=Rejected)
     * id (application id)
     * 
     * @Route("/{id}/status/{status}")
     * @Template()
     */
    public function statusAction(Request $request, $id, $status)
    {
        if (!empty($id) AND isset($status)){

            $em = $this->getDoctrine()->getManager();

            $applicationEntity = $em->getRepository('ModelBundle:Application')->find($id);

            if (!$applicationEntity) {
                throw $this->createNotFoundException('Unable to find Application entity.');
            }

            $applicationEntity->setApplicationStatus($status);

            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Selected application status was updated..');
            return $this->redirect($this->generateUrl('wit_sad_application_index'));
        }
    }

    /**
     * @Route("/one-time-editing/for/{id}")
     * @Method("GET")
     * @Template()
     */
    public function oneTimeEditingAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $applicationEntity = $em->getRepository('ModelBundle:Application')->find($id);

        if (!$applicationEntity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        $applicationEntity->setIsEditable(1);

        $em->flush();

        $this->get('session')->getFlashBag()->set('error', 'One-time editing was enabled for selected application!');
        return $this->redirect($this->generateUrl('wit_sad_application_index'));

        return array(
            'application' => $applicationEntity,
        );
    }

}