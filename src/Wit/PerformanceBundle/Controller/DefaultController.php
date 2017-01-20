<?php

namespace Wit\PerformanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Wit\ModelBundle\Entity\Course;
use Wit\ModelBundle\Entity\Marksheet;
use Wit\ModelBundle\Entity\UserLogs;

use Wit\ModelBundle\Entity\Transcript;
use Wit\ModelBundle\Entity\TranscriptDetail;

/**
 * Performance Default controller.
 * 
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     *
     * Display those courses whose performance was submitted by teacher
     * 
     * @Route("/")
     * @Route("/for/{course_id}/course", defaults={"course_id" = null})
     * 
     * @Template()
     */
    public function indexAction($course_id=null)
    {
        $em = $this->getDoctrine()->getManager();

        //getting all courses whose grading was submitted by teachers
        $courseEntities = $em->createQuery("SELECT partial c.{id, title, code} FROM ModelBundle:Course c WHERE c.isGradingSubmitted=1 ORDER BY c.id DESC")->getResult();

        $marksheets = null;

        //getting all marksheets for selected course
        if ($course_id){
            $marksheets = $em->createQuery("SELECT partial m.{id, nameEnglish, traineeid, totalMarks, remarks, dateCreated, courseTitle, courseCode, retakeNumber, isRetakeEnabled} FROM ModelBundle:Marksheet m WHERE m.courseId=".$course_id." ORDER BY m.id DESC")->getResult();
        }

        return array(
            'courseEntities' => $courseEntities,
            'marksheets' => $marksheets,
        );
    }

    /**
     *
     * Dispalying detail of individual performance
     * 
     * @Route("/{marksheet_id}/view")
     * 
     * @Template()
     */
    public function viewAction($marksheet_id)
    {
        if ($marksheet_id){

            $em = $this->getDoctrine()->getManager();

            $marksheetEntity = $em->getRepository('ModelBundle:Marksheet')->find($marksheet_id);

            $gradingCriteriaEntity = $em->getRepository('ModelBundle:GradingCriteria')->find(1);

            return array(
                'marksheet' => $marksheetEntity,
                'criteria' => $gradingCriteriaEntity,
            );
        }
    }

    /**
     *
     * Removing individual performance
     * Once any performance/marksheet is removed teacher will be able to submit it again from individual grading page not from bulk grading
     * 
     * @Route("/{marksheet_id}/remove")
     * 
     * @Template()
     */
    public function removeAction($marksheet_id)
    {
        if ($marksheet_id){

            $em = $this->getDoctrine()->getManager();

            $marksheetEntity = $em->getRepository('ModelBundle:Marksheet')->find($marksheet_id);

            if (!$marksheetEntity) {
                throw $this->createNotFoundException('Unable to find marksheet entity.');
            }

            $em->remove($marksheetEntity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Requested performance record was removed successfully..');
            return $this->redirect($this->generateUrl('wit_performance_default_index_1', array('course_id'=>$marksheetEntity->getCourseId())));
        }
    }

    /**
     *
     * Enabling retake for any performance / marksheet
     * Once any peformance is marked for retake, teacher will be able to submit grading again for it, previous performance will remain along with retakes
     * Retaken performance cannot be marked for retake only original performance / marksheet can be marked for retake
     * 
     * Once teacher submitted retake for any performance it should be visible along with all other performances for the same course
     * 
     * 1 = enable re-take
     * 0 = disable re-take
     * 
     * @Route("/{marksheet_id}/retake-status/{status}")
     * 
     * @Template()
     */
    public function retakeStatusAction($marksheet_id, $status)
    {
        if (isset($marksheet_id) AND isset($status)){

            $em = $this->getDoctrine()->getManager();

            $marksheetEntity = $em->getRepository('ModelBundle:Marksheet')->find($marksheet_id);
            if (!$marksheetEntity) {
                throw $this->createNotFoundException('Unable to find marksheet entity.');
            }

            //don't allow retake enable on marksheet which are already re-takes, so check if in any way if user is trying to submit retake for a retake
            if ($marksheetEntity->getRetakeNumber()){

            }else{
                //enable re-take for this performance
                $marksheetEntity->setIsRetakeEnabled($status);
            }
            
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Re-take was updated for requested performance record');
            return $this->redirect($this->generateUrl('wit_performance_default_index_1', array('course_id'=>$marksheetEntity->getCourseId())));
        }
    }

    /**
     *
     * Display those course whose performance was submitted by teacher
     * 
     * @Route("/quarter")
     * @Route("/for/{quarter_id}/quarter", defaults={"quarter_id" = null})
     * 
     * @Template()
     */
    public function byQuarterAction($quarter_id=null)
    {
        $em = $this->getDoctrine()->getManager();

        //getting all quarters
        $quarterEntities = $em->createQuery("SELECT partial q.{id, title, isCurrentQuarter} FROM ModelBundle:CourseQuarter q ORDER BY q.id ASC")->getResult();

        $courseEntities = null;
        $numberOfCoursesInSelectedQuarter = 0;
        $numberOfCoursesWithPerformanceCompleteStatusInSelectedQuarter = 0;

        $userSelectedQuarterEntity = null;

        //getting all courses in selected quarter
        if ( isset($quarter_id) OR $quarter_id != null ){
            $courseEntities = $em->createQuery("SELECT partial c.{id, title, code, isGradingSubmitted} FROM ModelBundle:Course c WHERE c.coursequarter=".$quarter_id." ORDER BY c.id ASC")->getResult();
            $numberOfCoursesInSelectedQuarter = count($courseEntities);

            //count number of courses having grading submitted status set to 1
            $coursesWithGradding = $em->createQuery("SELECT COUNT(c.id) FROM ModelBundle:Course c WHERE c.coursequarter=".$quarter_id." and c.isGradingSubmitted=1")->getResult();
            $numberOfCoursesWithPerformanceCompleteStatusInSelectedQuarter = $coursesWithGradding[0][1];

            // TODO
            // To be checked if all field to be sent..
            //$userSelectedQuarterEntity = $em->getRepository('ModelBundle:CourseQuarter')->find($quarter_id);
            $userSelectedQuarterEntity = $em->createQuery("SELECT q FROM ModelBundle:CourseQuarter q WHERE q.id=".$quarter_id." and q.areTranscriptsGenerated=1 ")->getResult();
        }

        // dump($userSelectedQuarterEntity);
        // exit;
        
        // echo $numberOfCoursesInSelectedQuarter;
        // echo "<br />";
        // echo $numberOfCoursesWithPerformanceCompleteStatusInSelectedQuarter;
        // exit;

        return array(
            'quarterEntities' => $quarterEntities,
            'courseEntities' => $courseEntities,
            'totalCourses' => $numberOfCoursesInSelectedQuarter,
            'totalCoursesWithGrading' => $numberOfCoursesWithPerformanceCompleteStatusInSelectedQuarter,
            'selectedQuarter' => $userSelectedQuarterEntity,
        );
    }

    /**
     * 
     * Generate transcripts for quarter whose performance is complete for all it's subjects / courses
     * 
     * @Route("/submit/for/{quarter_id}/quarter")
     * 
     * @Template()
     */
    public function submitForQuarterAction($quarter_id)
    {
        $em = $this->getDoctrine()->getManager();

        if (isset($quarter_id)){

            // transcript ids, 
            // these will be transcript whose more information needs to be updated that cannot be updated from inside of loop
            $transcriptsToUpdate = array();

            $marksheetEntities = $em->getRepository('ModelBundle:Marksheet')->findBy(array('quarterId'=>$quarter_id));

            foreach ($marksheetEntities as $marksheet) {
                
                //$transcriptEntity = $em->createQuery("SELECT trans FROM ModelBundle:Transcript trans WHERE trans.witId=:wit_id")->setParameter('wit_id', $marksheet->getTraineeid())->setMaxResults(1)->getOneOrNullResult();
                //$transcriptEntity = $em->getRepository('ModelBundle:Transcript')->findOneByWitId($marksheet->getTraineeid());

                //checking if user's record is already available in Transcript entity against each quarter number
                //because each user will have 1 entry in Transcript entity per quarter
                $transcriptEntity = $em->createQuery("SELECT partial trans.{id} FROM ModelBundle:Transcript trans WHERE trans.witId=:wit_id AND trans.quarterNumber=:quarterNumber")
                                    ->setParameters(array('wit_id'=>$marksheet->getTraineeid(), 'quarterNumber'=>$marksheet->getQuarterNumber()))
                                    ->setMaxResults(1)
                                    ->getOneOrNullResult();

                //this will help in adding details for any added transcript in detail entity
                $lastTranscriptId = 0;

                // dump($transcriptEntity);
                // exit;

                //Creating new transcript record if it's not already available in Transcript entity
                //TOBE CHEKECED: each trainee will have 1 record in Trascript entity per quarter, this will contribute in final transcript transcript generation
                if ( !$transcriptEntity ){

                    //Transcript was not found, create new Transcript

                    $transcript = new Transcript();

                    $transcript->setNameEnglish($marksheet->getNameEnglish());
                    $transcript->setNameArabic($marksheet->getNameArabic());
                    $transcript->setWitId($marksheet->getTraineeid());
                    // $transcript->setJoiningDate($marksheet->get());
                    $transcript->setQuarter($marksheet->getQuarter());
                    $transcript->setBatch($marksheet->getBatch());
                    $transcript->setProgram($marksheet->getProgram());
                    $transcript->setProgramCode($marksheet->getProgramCode());
                    $transcript->setBatchCode($marksheet->getBatchCode());
                    $transcript->setQuarterNumber($marksheet->getQuarterNumber());
                    
                    // $transcript->setTotalCreditHours();
                    // $transcript->setTotalPoints();
                    // $transcript->setGpa();
                    // $transcript->setAgpa();

                    $em->persist($transcript);
                    $em->flush(); //after flush i was able to get newly added entity id
                    
                    $lastTranscriptId = $transcript->getId();

                    $transcriptsToUpdate[] = $transcript->getId();

                }else{

                    //Transcript was found
                    $lastTranscriptId = $transcriptEntity->getId();

                    $transcriptsToUpdate[] = $transcriptEntity->getId();

                }

                //add details against Transcript Entity in TranscriptDetail Entity
                if ( $lastTranscriptId > 0 ) {

                    //don't allow duplicate TranscriptDetails against any Transcript Entity, checking it by course code
                    $transcriptDetailEntity = $em->createQuery("SELECT trans.id FROM ModelBundle:TranscriptDetail trans WHERE trans.code=:code AND trans.transcript=:transcript")
                                            ->setParameters(array('code'=>$marksheet->getCourseCode(), 'transcript'=>$lastTranscriptId))
                                            ->setMaxResults(1)
                                            ->getOneOrNullResult();
                    if( !$transcriptDetailEntity ){

                        $transcriptEntityInstance = $em->getRepository('ModelBundle:Transcript')->find($lastTranscriptId);

                        $transcriptDetail = new TranscriptDetail();
                        $transcriptDetail->setCourse($marksheet->getCourseTitle());
                        $transcriptDetail->setCode($marksheet->getCourseCode());
                        $transcriptDetail->setCreditHours($marksheet->getCreditHours());
                        $transcriptDetail->setMarks($marksheet->getTotalMarks());
                        $transcriptDetail->setGrade($marksheet->getGrade());
                        $transcriptDetail->setPoints($marksheet->getPoints());
                        $transcriptDetail->setSubjectTotalPoints($marksheet->getSubjectTotalPoints());
                        //$transcriptDetail->setHasRetakes($marksheet->get());
                        $transcriptDetail->setRemarks($marksheet->getRemarks());
                        $transcriptDetail->setTranscript($transcriptEntityInstance);

                        /*
                         * update Transcript Entity with more information based on TranscriptDetail Entity
                         */
                        $newTotalCreditHours = $transcriptEntityInstance->getTotalCreditHours()+$marksheet->getCreditHours();
                        $newTotalPoints = $transcriptEntityInstance->getTotalPoints()+$marksheet->getSubjectTotalPoints();
                        $transcriptEntityInstance->setTotalCreditHours($newTotalCreditHours);
                        $transcriptEntityInstance->setTotalPoints($newTotalPoints);

                        // below detail will be updated after loop
                        // $newGPA = $transcriptEntityInstance->getGpa()+($newTotalPoints/$newTotalCreditHours);
                        // $transcriptEntityInstance->setGpa($newGPA);
                        // $transcriptEntityInstance->setAgpa();

                        $em->persist($transcriptDetail);
                        $em->flush();
                    }

                }// END: lastTranscriptId > 0, check

            }// END: Marksheets foreach loop

            /*
             * update Transcript Entity with more information that cannot be updated from within the loop
             */
            $transcriptsForUpdate = array_unique($transcriptsToUpdate); //array_unique is applied to removed multiple entries of same transcript
            if (isset($transcriptsForUpdate)){
                foreach ($transcriptsForUpdate as $key => $tid) {

                    $transcriptEntity = $em->getRepository('ModelBundle:Transcript')->find($tid);
                    if ($transcriptEntity){
                        if ( $transcriptEntity->getTotalPoints() ){
                            $transcriptEntity->setGpa($transcriptEntity->getTotalPoints()/$transcriptEntity->getTotalCreditHours());
                            // $transcriptEntity->setAgpa();
                            $em->flush();
                        }
                    }

                    // updating AGPA for Transcript Entity
                    /*
                        echo $transcriptEntity->getWitId()."{$key}<br />"; ///////////////////////
                        echo "hello";
                        exit;
                    */
                    
                    $totalPointsAllPreviousQuarters = 0;
                    $totalCreditHoursAllPreviousQuarters = 0;

                    $transcriptEntities = $em->createQuery("SELECT tr FROM ModelBundle:Transcript tr WHERE tr.programCode=:programCode AND tr.batchCode=:batchCode AND tr.witId=:witId")
                                        ->setParameters(array(
                                            'programCode'=>$transcriptEntity->getProgramCode(), 
                                            'batchCode'=>$transcriptEntity->getBatchCode(), 
                                            'witId'=>$transcriptEntity->getWitId()
                                            ))
                                        ->getResult();
                    foreach ($transcriptEntities as $transcript) {
                        //echo "Transcript id: ".$transcript->getId()."<br />";
                        $totalPointsAllPreviousQuarters = ($totalPointsAllPreviousQuarters+$transcript->getTotalPoints());
                        $totalCreditHoursAllPreviousQuarters = ($totalCreditHoursAllPreviousQuarters+$transcript->getTotalCreditHours());
                    }

                    $AGPA = ($totalPointsAllPreviousQuarters/$totalCreditHoursAllPreviousQuarters);
                    $transcriptEntity->setAgpa($AGPA);
                    $em->flush();

                    //echo $transcriptEntity->getId()." - Total Points: ".$totalPointsAllPreviousQuarters." - Total Credit Hours: ".$totalCreditHoursAllPreviousQuarters."<br />";

                }// End: foreach

                //exit;

                unset($transcriptsToUpdate);
            }

            //updating flags for selected / submitted quarter
            $quarterEntity = $em->getRepository('ModelBundle:CourseQuarter')->find($quarter_id);
            $quarterEntity->setAreTranscriptsGenerated(1);
            $em->flush();

            //exit;

            $this->get('session')->getFlashBag()->set('error', 'Transcripts Generated for Selected Quarter');
            return $this->redirect($this->generateUrl('wit_performance_default_byquarter_1', array('quarter_id'=>$quarter_id)));
            
        }// END: Quarter id check

        


        //getting all quarters
        $quarterEntities = $em->createQuery("SELECT partial q.{id, title, isCurrentQuarter} FROM ModelBundle:CourseQuarter q ORDER BY q.id ASC")->getResult();

        $courseEntities = null;
        $numberOfCoursesInSelectedQuarter = 0;
        $numberOfCoursesWithPerformanceCompleteStatusInSelectedQuarter = 0;

        //getting all courses in selected quarter
        if ( isset($quarter_id) ){
            $courseEntities = $em->createQuery("SELECT partial c.{id, title, code, isGradingSubmitted} FROM ModelBundle:Course c WHERE c.coursequarter=".$quarter_id." ORDER BY c.id ASC")->getResult();
            $numberOfCoursesInSelectedQuarter = count($courseEntities);

            //count number of courses having grading submitted status set to 1
            $coursesWithGradding = $em->createQuery("SELECT COUNT(c.id) FROM ModelBundle:Course c WHERE c.coursequarter=".$quarter_id." and c.isGradingSubmitted=1")->getResult();
            $numberOfCoursesWithPerformanceCompleteStatusInSelectedQuarter = $coursesWithGradding[0][1];
        }
        
        // echo $numberOfCoursesInSelectedQuarter;
        // echo "<br />";
        // echo $numberOfCoursesWithPerformanceCompleteStatusInSelectedQuarter;
        // exit;

        return array(
            'quarterEntities' => $quarterEntities,
            'courseEntities' => $courseEntities,
            'totalCourses' => $numberOfCoursesInSelectedQuarter,
            'totalCoursesWithGrading' => $numberOfCoursesWithPerformanceCompleteStatusInSelectedQuarter,
        );
    }

    /**
     *
     * Allows user to filter by Quarter, Course and Student and view Transcript
     * 
     * @Route("/history")
     * @Route("/history/{program_id}", defaults={"program_id" = null})
     * @Route("/history/{program_id}/{batch_id}", defaults={"program_id" = null, "batch_id" = null})
     * @Route("/history/{program_id}/{batch_id}/{quarter_id}", defaults={"program_id" = null, "batch_id" = null, "quarter_id" = null})
     * @Route("/history/{program_id}/{batch_id}/{quarter_id}/{course_id}", defaults={"program_id" = null, "batch_id" = null, "quarter_id" = null, "course_id" = null})
     * @Route("/history/{program_id}/{batch_id}/{quarter_id}/{course_id}/{user_wit_id}", defaults={"program_id" = null, "batch_id" = null, "quarter_id" = null, "course_id" = null, "user_wit_id" = null})
     * 
     * @Template()
     */
    public function historyAction($program_id=null, $batch_id=null, $quarter_id=null, $course_id=null, $user_wit_id=null)
    {
        $em = $this->getDoctrine()->getManager();

        //this will record user selection.. e-g category, batch, quarter and course
        $userSelectedProgram = null;
        $userSelectedBatch = null;
        $userSelectedQuarter = null;
        $userSelectedCourse = null;

        $userSelectedUser = null;

        $programs = $em->getRepository('ModelBundle:CourseCategory')->findBy(array(), array('id' => 'DESC'));

        $batches = null;
        $quarters = null;
        $courses = null;
        $enrolments = null;
        $transcripts = null;

        if ($program_id){
            $batches = $em->getRepository('ModelBundle:CourseBatch')->findBy(array('coursecategory'=>$program_id), array('id' => 'ASC'));
            $userSelectedProgram = $program_id;
        }

        if ($batch_id){
            $quarters = $em->getRepository('ModelBundle:CourseQuarter')->findBy(array('coursebatch'=>$batch_id), array('id' => 'ASC'));
            $userSelectedBatch = $batch_id;
        }

        if ($quarter_id){
            $courses = $em->getRepository('ModelBundle:Course')->findBy(array('coursequarter'=>$quarter_id), array('id' => 'ASC'));
            $userSelectedQuarter = $quarter_id;
        }

        if ($course_id){
            //loading users with student role in selected course
            $enrolments = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$course_id), array('id' => 'ASC'));
            $userSelectedCourse = $course_id;
        }

        if ($user_wit_id){
            //loading transcripts for selected user
            $transcripts = $em->getRepository('ModelBundle:Transcript')->findBy(array('witId'=>$user_wit_id), array('quarterNumber' => 'ASC'));
            $userSelectedUser = $user_wit_id;
        }

        return array(
            'programs' => $programs,
            'batches' => $batches,
            'quarters' => $quarters,
            'courses' => $courses,
            'enrolments' => $enrolments,
            'transcripts' => $transcripts,

            'userSelectedProgram' => $userSelectedProgram,
            'userSelectedBatch' => $userSelectedBatch,
            'userSelectedQuarter' => $userSelectedQuarter,

            'userSelectedCourse' => $userSelectedCourse,
            'userSelectedUser' => $userSelectedUser,
        );
    }

    /**
     *
     * Allows user to view performance detail of any quarter from filtered performance record
     * 
     * @Route("/history-detail/{transcript_id}")
     * 
     * @Template()
     */
    public function historyDetailAction($transcript_id)
    {
        if($transcript_id){

            $em = $this->getDoctrine()->getManager();

            $transcriptDetails = $em->getRepository('ModelBundle:TranscriptDetail')->findBy(array('transcript'=>$transcript_id), array('id' => 'ASC'));

            return array(
                'transcriptDetails' => $transcriptDetails,
            );
        }
    }












    /**
	 * 
	 * This will display consolidated performance sheet for selected course/subject
	 * 
     * @Route("/sheet/for/{course_id}/")
     * 
     * @Template()
     */
    public function viewSheetAction($course_id)
    {
        $em = $this->getDoctrine()->getManager();

        //because this module will be called by ADMIN,TOD,SAD,DH so user role should be determined to display performance sheets for them
        $onlineUserRole = trim($this->get('security.context')->getToken()->getUser()->getRoles()[0]);
        
        // if ($onlineUserRole == "ROLE_DH") {
        //     $entities = $em->getRepository('ModelBundle:Marksheet')->findBy(array('courseId'=>$course_id, 'isCheckedByDH'=>0), array('id' => 'DESC'));
        // }else if ($onlineUserRole == "ROLE_TOD") {
        //     $entities = $em->getRepository('ModelBundle:Marksheet')->findBy(array('courseId'=>$course_id, 'isCheckedByDH'=>1), array('id' => 'DESC'));
        // }else if ($onlineUserRole == "ROLE_SAD") {
        //     $entities = $em->getRepository('ModelBundle:Marksheet')->findBy(array('courseId'=>$course_id, 'isCheckedByTOM'=>1), array('id' => 'DESC'));
        // }else{
        //     //Admin is intended; TODO: to be checked to ensure..
        //     $entities = $em->getRepository('ModelBundle:Marksheet')->findBy(array('courseId'=>$course_id), array('id' => 'DESC'));
        // }

        $entities = $em->getRepository('ModelBundle:Marksheet')->findBy(array('courseId'=>$course_id), array('id' => 'DESC'));

        $courseEntity = $em->getRepository('ModelBundle:Course')->find($course_id);

        return array(
            'entities' => $entities,
            'course' => $courseEntity,
            'onlineUserRole' => $onlineUserRole,
        );
    }

    //TODO: ALL BELOW

    /**
     * 
     * This will submit the submitted marksheet / performance sheet to respective user role
     * Submitting to user will be determined based on current online user
     * Role heirarcy: DH, TOM, SAD
     *  
     * @Route("/sheet/{marksheet_id}/against/{course_id}")
     * 
     * @Template()
     */
    public function submitSheetAction($course_id, $marksheet_id)
    {
    	$onlineUserRole = trim($this->get('security.context')->getToken()->getUser()->getRoles()[0]);

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ModelBundle:Marksheet')->find($marksheet_id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find marksheet entity.');
        }

        //because this is sole action for submitting performances by DH, TOM, SAD it should be checking who submitted it and to whoom
        if ($onlineUserRole == "ROLE_DH") {
        	$entity->setIsCheckedByDH(1);
        }else if ($onlineUserRole == "ROLE_TOD") {
        	$entity->setIsCheckedByTOM(1);
        }else if ($onlineUserRole == "ROLE_SAD") {
        	$entity->setIsCheckedBySAD(1); //TODO: it needs to be checked if SAD will submit and how..
        }
        
        $em->flush();

        /*
            Log performance record submission..
        */
        $userLog = new UserLogs();
        $userLog->setDetail("has submitted a performance record for your review and action..");
        $userLog->setGenerator("TOD");
        $userLog->setDateCreated(new \DateTime());
        $userLog->setIsActive(1);
        if ($onlineUserRole == "ROLE_DH") {
        	$userLog->setIsForTod(1); //if 1 it will display in TOD's notifications	
        }else if ($onlineUserRole == "ROLE_TOD") {
        	$userLog->setIsForSad(1); //if 1 it will display in SAD's notifications
        }else if ($onlineUserRole == "ROLE_SAD") {
        	//TODO: to be decided if SAD submits the performance who will get notified..
        }
        $em->persist($userLog);
        $em->flush();

        $this->get('session')->getFlashBag()->set('error', 'A Performance record was approved and submitted to next authority..');
        return $this->redirect($this->generateUrl('wit_performance_default_viewsheet', array("course_id"=>$course_id)));
    }

    /**
     * 
     * This will approve current consolidated performance sheet for the subject
     * to TOM, SAD or STUDENTs based on current online user role, if DH is submiting TOM will see, if TOM is submiting SAD will see, if SAD is Submiting Students will see
     * Role heirarcy: DH, TOM, SAD, STUDENT
     *  
     * @Route("/approval/for/{course_id}")
     * 
     * @Template()
     */
    public function approvalForAction($course_id)
    {
        $onlineUserRole = trim($this->get('security.context')->getToken()->getUser()->getRoles()[0]);
        $onlineUserFullName = trim($this->get('security.context')->getToken()->getUser()->getFirstname().' '.$this->get('security.context')->getToken()->getUser()->getLastname());

        $em = $this->getDoctrine()->getManager();

        //getting course entity
        $courseEntity = $em->getRepository('ModelBundle:Course')->find($course_id);

        if (!$courseEntity) {
            throw $this->createNotFoundException('Unable to find course entity.');
        }

        /*
            Because it's sole action to submit consolidated performance sheet for selected source to TOM, SAD and STUDENTs
        */
        if ($onlineUserRole == "ROLE_DH") {
            $courseEntity->setPerformancesheetApproval(1); //currently it's with TOM
        }else if ($onlineUserRole == "ROLE_TOD") {
            $courseEntity->setPerformancesheetApproval(2); //currently it's with SAD
        }else if ($onlineUserRole == "ROLE_SAD") {
            $courseEntity->setPerformancesheetApproval(3); //currently it's with STUDENTs to see
        }
        
        //$em->flush();

        /*
            Log consolidated performance sheet submission to respective user role / users
        */
        $userLog = new UserLogs();
        $userLog->setDetail("submitted a consolidated performance sheet for ".$courseEntity->getTitle()." <a href='".$this->generateUrl('wit_performance_default_viewsheet', array('course_id'=>$course_id))."'>Read More..</a>");
        $userLog->setGenerator($onlineUserFullName);
        $userLog->setDateCreated(new \DateTime());
        $userLog->setIsActive(1);
        if ($onlineUserRole == "ROLE_DH") {
            $userLog->setIsForTod(1); //if 1 it will display in TOD's notifications 
        }else if ($onlineUserRole == "ROLE_TOD") {
            $userLog->setIsForSad(1); //if 1 it will display in SAD's notifications
        }else if ($onlineUserRole == "ROLE_SAD") {
            //TODO: to be decided if SAD submits it how users will be notified or not be notified..? may bee all users whose performance sheet is being approved will be able to get notification individually
        }
        $em->persist($userLog);

        $em->flush();

        $this->get('session')->getFlashBag()->set('error', 'Consolidated performance sheet was approved..');
        return $this->redirect($this->generateUrl('wit_performance_default_viewsheet', array("course_id"=>$course_id)));
    }

}
