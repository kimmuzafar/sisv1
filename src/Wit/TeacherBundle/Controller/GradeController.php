<?php

namespace Wit\TeacherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;

use Wit\ModelBundle\Entity\Marksheet;
use Wit\TeacherBundle\Form\GradeType;

use Wit\ModelBundle\Entity\UserLogs;

/**
 * Grade controller.
 *
 * @Route("/grade")
 */
class GradeController extends Controller
{
    /**
     * This action will display all course this teacher is enrolled in
     * 
     * @Route("/me/{user_id}/in/{course_id}")
     * 
     * @Template()
     */
    public function indexAction($user_id, $course_id)
    {
        $em = $this->getDoctrine()->getManager();

        //echo $em->getRepository('ModelBundle:GradingCriteria')->find(1)->getGroupWork();
        //exit;

        $courseEntity = $em->getRepository('ModelBundle:Course')->find($course_id);

        //don't allow navigation to this page if grading is disabled by TOM
        if ($courseEntity->getIsAllowedGrading()==0) {
            $this->get('session')->getFlashBag()->set('notice', 'Grading for that course was disabled by TOM..');
            return $this->redirect($this->generateUrl('wit_teacher_course_index'));   
        }

        $userEntity = $em->getRepository('ModelBundle:User')->find($user_id);
        $gradingCriteriaEntity = $em->getRepository('ModelBundle:GradingCriteria')->find(1);

        //form to create
        $addMarksheetForm = $this->createCreateForm(new Marksheet());

        $customDataArray = array(
            'user' => $user_id,
            'course' => $course_id,
            'teacher' => $this->get('security.context')->getToken()->getUser()->getId(),
            'teacher_name' => $this->get('security.context')->getToken()->getUser()->getFirstname()." ".$this->get('security.context')->getToken()->getUser()->getLastname(),
        );

        return array(
            'addMarksheetForm' => $addMarksheetForm->createView(),
            'user' => $userEntity,
            'criteria' => $gradingCriteriaEntity,
            'course' => $courseEntity,
            'customData' => $customDataArray,
        );
    }

    /**
     * Creates a form to create an entity.
     *
     * @param Marksheet $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Marksheet $entity)
    {
        $form = $this->createForm(new GradeType(), $entity, array(
            'action' => $this->generateUrl('wit_teacher_grade_new'),
            'method' => 'POST',
            'attr' => array(
                'id'    => 'add-new-form',
            )
        ));

        return $form;
    }

    /**
     * add new information to marksheet entity for respective student and course.
     *
     * @Route("/new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        //check if form was submitted
        if ($request->isMethod('POST')){

            $marksheet = new Marksheet();

            //addMarksheetForm to create an entity.
            $addMarksheetForm = $this->createCreateForm($marksheet);

            $addMarksheetForm->handleRequest($request);

            if ($addMarksheetForm->isValid()) {

                $em = $this->getDoctrine()->getManager();


                $formData = $request->request->all();
                $formFieldData = $formData['wit_modelbundle_marksheet'];

                //echo "<pre>";
                //var_dump($formData);
                //echo "</pre>";
                //echo $formData['finalExamMarksCriteria']*30/100;
                //exit;

                $marksheet->setCourseTitle($formData['course_title']);
                $marksheet->setCourseCode($formData['course_code']);
                $marksheet->setCreditHours($formData['course_credit_hours']);/////////

                $marksheet->setProgram($formData['course_category']);
                $marksheet->setBatch($formData['course_batch']);
                $marksheet->setQuarter($formData['course_quarter']);
                $marksheet->setQuarterId($formData['quarter_id']);

                $marksheet->setProgramCode($formData['course_category_code']);////////
                $marksheet->setBatchCode($formData['course_batch_code']);
                $marksheet->setQuarterNumber($formData['quarter_number']);

                $marksheet->setSid($formData['user_id']);
                $marksheet->setNameEnglish($formData['studentNameEnglish']);
                $marksheet->setNameArabic($formData['studentNameArabic']);
                $marksheet->setTraineeid($formData['traineeid']);

                $marksheet->setGroupWork($formFieldData['groupWork']);
                $marksheet->setAssignment($formFieldData['assignment']);
                $marksheet->setQuiz($formFieldData['quiz']);
                $marksheet->setAttendance($formFieldData['attendance']);
                $marksheet->setFinalExam($formFieldData['finalExam']);

                $totalMarks = ($formFieldData['groupWork']+$formFieldData['assignment']+$formFieldData['quiz']+$formFieldData['attendance']+$formFieldData['finalExam']);
                $marksheet->setTotalMarks($totalMarks);

                //getting user grade based on total marks ///////////
                $gradingSystemEntity = $em->createQuery("SELECT gs FROM ModelBundle:GradingSystem gs WHERE :totalMarks BETWEEN gs.marksMin AND gs.marksMax")->setParameter('totalMarks', $totalMarks)->setMaxResults(1)->getOneOrNullResult();
                //echo $gradingSystemEntity->getGradeLetter();

                $marksheet->setGrade($gradingSystemEntity->getGradeLetter());//////////
                $marksheet->setPoints($gradingSystemEntity->getEarnedPoints());////////////
                $marksheet->setSubjectTotalPoints(($formData['course_credit_hours']*$gradingSystemEntity->getEarnedPoints()));////////////

                //TOBE CHECKED:
                //Final Exam Marks Criteria * 30% / 100 = 12
                $finalExam30Percent = $formData['finalExamMarksCriteria']*30/100;
                if( ($formFieldData['finalExam']>$finalExam30Percent) AND ($totalMarks>59) ){ //59 came from "Grading System Table, which is fail"
                    $marksheet->setRemarks("Pass");
                }else{
                    $marksheet->setRemarks("Fail");
                }

                $marksheet->setTeacherName($formData['teacher_name']);

                $marksheet->setIsCheckedByDH(0);
                $marksheet->setIsCheckedByTOM(0);
                $marksheet->setIsCheckedBySAD(0);
                $marksheet->setIsCheckedByHR(0);
                $marksheet->setIsReleasedForStudent(0);

                $marksheet->setStudentId($formData['user_id']);
                $marksheet->setCourseId($formData['course_id']);
                $marksheet->setTeacherId($formData['teacher_id']);
                $marksheet->setDateCreated(new \DateTime());

                $em->persist($marksheet);
                $em->flush();

                /*
                    Teacher has graded the student create the log for DH, Admin
                */
                $userLog = new UserLogs();
                $userLog->setDetail($formData['teacher_name']." has submitted grading for student Mr. ".$formData['studentNameEnglish']);
                $userLog->setGenerator("Teacher");
                $userLog->setDateCreated(new \DateTime());
                $userLog->setIsActive(1);

                $userLog->setIsForDh(1); //if 1 it will display in DH's notifications

                $em->persist($userLog);
                $em->flush();

                /*
                 * record was created..
                 * Redirect user
                 */
                $this->get('session')->getFlashBag()->set('error', 'Student named '.$formData['studentNameEnglish'].' was graded successfully..');
                return $this->redirect($this->generateUrl('wit_teacher_course_view', array('course_id'=>$formData['course_id'])));
            }

        }
    }

    /**
     * Bulk grading for all enrolled users.. in selected subject/course
     *
     * @Route("/{course_id}/in/bulk")
     * @Template()
     */
    public function bulkAction($course_id)
    {
        if($course_id){

            $em = $this->getDoctrine()->getManager();

            //getting course entity
            $courseEntity = $em->getRepository('ModelBundle:Course')->find($course_id);

            //don't allow navigation to this page if grading is disabled by TOM
            if ($courseEntity->getIsAllowedGrading()==0) {
                $this->get('session')->getFlashBag()->set('notice', 'Grading for that course was disabled by TOM..');
                return $this->redirect($this->generateUrl('wit_teacher_course_index'));   
            }

            /* teacher cannot submit grading again if submitted once */
            if ($courseEntity->getIsGradingSubmitted()) {
                $this->get('session')->getFlashBag()->set('error', 'Grading for this course has already been submitted..!');
                return $this->redirect($this->generateUrl('wit_teacher_course_index'));   
            }

            $gradingCriteriaEntity = $em->getRepository('ModelBundle:GradingCriteria')->find(1);

            //getting enrolment data
            $enrolmentData = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$courseEntity), array('id' => 'ASC'));

            //getting all users from marksheet entity to identify who is already enrolled in selecte subject to not show him
            $marksheets = $em->getRepository('ModelBundle:Marksheet')->findBy(array('courseId'=>$course_id), array());
            $gradedUsers = array();
            foreach ($marksheets as $marksheet) {
                $gradedUsers[] = $marksheet->getStudentId();
            }
            //dump($gradedUsers);
            //exit;

            return array(
                'course' => $courseEntity,
                'enrolmentData' => $enrolmentData,
                'criteria' => $gradingCriteriaEntity,
                'alreadyGradedUsers' => $gradedUsers, //sending to view to not to display users who are already graded
            );   
        }
    }

    /**
     * Bulk grading for all enrolled users.. in selected subject/course
     *
     * @Route("/bulk/submit")
     * @Template()
     */
    public function bulkSubmitAction(Request $request)
    {
        //exit;

        //check if form was submitted
        if ($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();

            // echo "submitted..<br />";

            $formData = $request->request->all();
            
            // echo "<pre>";
            // var_dump($formData);
            // echo "</pre>";

            for ($i=0; $i < count($formData['user_id']); $i++) {
                //echo $formData['user_id'][$i]." ".$formData['studentNameEnglish'][$i]." "."<br />";

                $marksheet = new Marksheet();

                $marksheet->setCourseTitle($formData['course_title'][$i]);
                $marksheet->setCourseCode($formData['course_code'][$i]);
                $marksheet->setCreditHours($formData['course_credit_hours'][$i]);/////////

                $marksheet->setProgram($formData['course_category'][$i]);
                $marksheet->setBatch($formData['course_batch'][$i]);
                $marksheet->setQuarter($formData['course_quarter'][$i]);
                $marksheet->setQuarterId($formData['quarter_id'][$i]);

                $marksheet->setProgramCode($formData['course_category_code'][$i]);////////
                $marksheet->setBatchCode($formData['course_batch_code'][$i]);
                $marksheet->setQuarterNumber($formData['quarter_number'][$i]);

                $marksheet->setSid($formData['user_id'][$i]);
                $marksheet->setNameEnglish($formData['studentNameEnglish'][$i]);
                $marksheet->setNameArabic($formData['studentNameArabic'][$i]);
                $marksheet->setTraineeid($formData['traineeid'][$i]);

                $marksheet->setGroupWork($formData['groupWork'][$i]);
                $marksheet->setAssignment($formData['assignment'][$i]);
                $marksheet->setQuiz($formData['quiz'][$i]);
                $marksheet->setAttendance($formData['attendance'][$i]);
                $marksheet->setFinalExam($formData['finalExam'][$i]);

                $totalMarks = ($formData['groupWork'][$i]+$formData['assignment'][$i]+$formData['quiz'][$i]+$formData['attendance'][$i]+$formData['finalExam'][$i]);
                $marksheet->setTotalMarks($totalMarks);

                //getting user grade based on total marks ///////////
                $gradingSystemEntity = $em->createQuery("SELECT gs FROM ModelBundle:GradingSystem gs WHERE :totalMarks BETWEEN gs.marksMin AND gs.marksMax")->setParameter('totalMarks', $totalMarks)->setMaxResults(1)->getOneOrNullResult();
                //echo $gradingSystemEntity->getGradeLetter();

                $marksheet->setGrade($gradingSystemEntity->getGradeLetter());//////////
                $marksheet->setPoints($gradingSystemEntity->getEarnedPoints());////////////
                $marksheet->setSubjectTotalPoints(($formData['course_credit_hours'][$i]*$gradingSystemEntity->getEarnedPoints()));////////////

                //TOBE CHECKED:
                //Final Exam Marks Criteria * 30% / 100 = 12
                $finalExam30Percent = $formData['finalExamMarksCriteria'][$i]*30/100;
                if( ($formData['finalExam'][$i]>$finalExam30Percent) AND ($totalMarks>59) ){ //59 came from "Grading System Table, which is fail"
                    $marksheet->setRemarks("Pass");
                }else{
                    $marksheet->setRemarks("Fail");
                }

                $marksheet->setTeacherName($formData['teacher_name'][$i]);

                $marksheet->setIsCheckedByDH(0);
                $marksheet->setIsCheckedByTOM(0);
                $marksheet->setIsCheckedBySAD(0);
                $marksheet->setIsCheckedByHR(0);
                $marksheet->setIsReleasedForStudent(0);

                $marksheet->setStudentId($formData['user_id'][$i]);
                $marksheet->setCourseId($formData['course_id'][$i]);
                $marksheet->setTeacherId($formData['teacher_id'][$i]);
                $marksheet->setDateCreated(new \DateTime());

                $em->persist($marksheet);
                $em->flush();
            }

            $em = $this->getDoctrine()->getManager();

            /*
                updating isGradingSubmitted so teacher cannot submit grading for the course again..
            */
            $courseEntity = $em->getRepository('ModelBundle:Course')->find($formData['course_id'][0]);
            $courseEntity->setIsGradingSubmitted(1);
            $courseEntity->setIsAllowedGrading(0);
            $em->flush();

            /*
                Teacher has graded students in this course, create the log for DH, Admin
            */
            $userLog = new UserLogs();
            $userLog->setDetail("Submitted grading in bulk for ".$formData['course_title'][0]." <a href='".$this->generateUrl('wit_performance_default_viewsheet', array('course_id'=>$formData['course_id'][0]))."'>Read More..</a>");
            $userLog->setGenerator($formData['teacher_name'][0]);
            $userLog->setDateCreated(new \DateTime());
            $userLog->setIsActive(1);
            $userLog->setIsForDh(1); //if 1 it will display in DH's notifications
            $em->persist($userLog);
            $em->flush();

            /*
             * records were created..
             * Redirect user
             */
            $this->get('session')->getFlashBag()->set('error', 'Grading was submitted for '.$formData['course_title'][0]);
            return $this->redirect($this->generateUrl('wit_teacher_course_index'));
        }   
    }

    /**
     * Grading per user in selected course
     *
     * @Route("/per-user-in-course")
     * @Route("/{course_id}/course", defaults={"course_id" = null})
     * 
     * @Template()
     */
    public function perUserInCourseAction(Request $request, $course_id=null)
    {
        $em = $this->getDoctrine()->getManager();

        $currentOnlineUserId = $this->get('security.context')->getToken()->getUser()->getId();

        //getting all enrolments against online user, to get courses he is currently enrolled in
        $enrolmentData = $em->createQuery("SELECT partial en.{id, course} FROM ModelBundle:Enrolment en WHERE en.user=:user ORDER BY en.id DESC")->setParameter("user", $currentOnlineUserId)->getResult();

        $gradingCriteriaEntity = null;

        //if user selected the course, grading criteria should be fetched and sent to view to display
        if ($course_id){
            $gradingCriteriaEntity = $em->getRepository('ModelBundle:GradingCriteria')->find(1);

            //TODOOOOOOO:......... onward

            //getting enrolled students in selected subject 
            $enrolmentData = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$courseEntity), array('id' => 'ASC'));
        }

        if ($request->isMethod('POST')){

            $formData = $request->request->all();

            exit; ////TODO:

            //getting course entity
            $courseEntity = $em->getRepository('ModelBundle:Course')->find($course_id);

            //don't allow navigation to this page if grading is disabled by TOM
            if ($courseEntity->getIsAllowedGrading()==0) {
                $this->get('session')->getFlashBag()->set('notice', 'Grading for that course was disabled by TOM..');
                return $this->redirect($this->generateUrl('wit_teacher_course_index'));   
            }

            /* teacher cannot submit grading again if submitted once */
            if ($courseEntity->getIsGradingSubmitted()) {
                $this->get('session')->getFlashBag()->set('error', 'Grading for this course has already been submitted..!');
                return $this->redirect($this->generateUrl('wit_teacher_course_index'));   
            }

            $gradingCriteriaEntity = $em->getRepository('ModelBundle:GradingCriteria')->find(1);

            //getting enrolment data
            $enrolmentData = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$courseEntity), array('id' => 'ASC'));

            //getting all users from marksheet entity to identify who is already enrolled in selecte subject to not show him
            $marksheets = $em->getRepository('ModelBundle:Marksheet')->findBy(array('courseId'=>$course_id), array());
            $gradedUsers = array();
            foreach ($marksheets as $marksheet) {
                $gradedUsers[] = $marksheet->getStudentId();
            }
            //dump($gradedUsers);
            //exit;

            return array(
                'course' => $courseEntity,
                'enrolmentData' => $enrolmentData,
                'criteria' => $gradingCriteriaEntity,
                'alreadyGradedUsers' => $gradedUsers, //sending to view to not to display users who are already graded
            );

        }//end: post check

        $marksheets = null;

        return array(
            'enrolmentData' => $enrolmentData,
            'criteria' => $gradingCriteriaEntity,
            'marksheets' => $marksheets,
        );
    }

}