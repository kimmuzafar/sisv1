<?php

namespace Wit\TeacherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;

use Wit\ModelBundle\Entity\Course;

/**
 * Course controller.
 *
 * @Route("/course")
 */
class CourseController extends Controller
{
    /**
     * This action will display all course this teacher is enrolled in
     * 
     * @Route("/")
     * 
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        //Getting online user instance
        $user = $em->getRepository('ModelBundle:User')->find($this->get('security.context')->getToken()->getUser()->getId());
        //Getting all enrolments against online user
        $enrolmentData = $em->getRepository('ModelBundle:Enrolment')->findBy(array('user'=>$user), array('id' => 'DESC'));

        return array(
            'enrolmentData' => $enrolmentData
        );
    }

    /**
     * This action will display user selected course details
     *
     * @Route("/view/{course_id}")
     * @Template()
     */
    public function viewAction($course_id)
    {
        if($course_id){

            $em = $this->getDoctrine()->getManager();

            //getting course entity
            $courseEntity = $em->getRepository('ModelBundle:Course')->find($course_id);
            if (!$courseEntity) {
                throw $this->createNotFoundException('Unable to find course entity.');
            }

            //getting enrolment data
            $enrolmentData = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$courseEntity), array('id' => 'ASC'));

            //getting student ids from marksheet entity to hide grade me button in view
            $userMarksheet = $em->getRepository('ModelBundle:Marksheet')->findBy(array('courseId'=>$course_id), array('id' => 'DESC'));
            $marksheetUsersArray = array();
            foreach ($userMarksheet as $mark) {
                $marksheetUsersArray[] = $mark->getStudentId();
            }

            return array(
                'course' => $courseEntity,
                'enrolmentData' => $enrolmentData,
                'marksheetStudents' => $marksheetUsersArray,
            );   
        }
    }

    /**
     * 
     * TODO: Security check, block teacher trying to access other subjects retakes through url, teacher must be enrolled in course before seeing retakes in it
     * 
     * This action allows user to filter by course to find re-takes enabled by TOM
     * In course filter dropdown only those course will display where current online user is enrolled in
     * 
     * @Route("/re-takes")
     * @Route("/{course_id}/re-takes", defaults={"course_id" = null})
     * 
     * @Template()
     */
    public function retakesAction(Request $request, $course_id=null)
    {
        $em = $this->getDoctrine()->getManager();

        $currentOnlineUserId = $this->get('security.context')->getToken()->getUser()->getId();

        //getting all enrolments against online user, to get courses he is currently enrolled in
        $enrolmentData = $em->createQuery("SELECT partial en.{id, course} FROM ModelBundle:Enrolment en WHERE en.user=:user ORDER BY en.id DESC")->setParameter("user", $currentOnlineUserId)->getResult();

        $marksheets = null;
        $gradingCriteriaEntity = null;

        //getting all marksheets who has is_retake_enabled set to 1 for selected course
        if ($course_id){
            $marksheets = $em->createQuery("SELECT partial m.{id, nameEnglish, traineeid, totalMarks, remarks, dateCreated, courseTitle, courseCode, retakeNumber, isRetakeEnabled, groupWork, assignment, quiz, attendance, finalExam} FROM ModelBundle:Marksheet m WHERE m.courseId=".$course_id." AND m.isRetakeEnabled=1 ORDER BY m.id DESC")->getResult();

            //if re-takes are avilable grading criteria should be fetched and sent to view to display
            $gradingCriteriaEntity = $em->getRepository('ModelBundle:GradingCriteria')->find(1);
        }

        //check if any retake was submitted
        if ($request->isMethod('POST')){

            $formData = $request->request->all();

            // echo "<pre>";
            // var_dump($formData);
            // echo "</pre>";

            // exit;

            //getting marksheet entity which has re-take enabled but is not re-take
            $marksheetEntity = $em->getRepository('ModelBundle:Marksheet')->find($formData['marksheet_id']);
            if (!$marksheetEntity) {
                throw $this->createNotFoundException('Unable to find Marksheet entity.');
            }

            //getting those marksheets which are actually re-takes to get re-take number and store in last re-take
            $previousRetakesEntities = $em->createQuery("SELECT m.id FROM ModelBundle:Marksheet m WHERE m.studentId=".$marksheetEntity->getStudentId()." AND m.courseId=".$marksheetEntity->getCourseId()." AND m.retakeNumber IS NOT NULL AND m.retakeNumber != 0 ORDER BY m.id DESC")->getResult();
            $newRetakeNumber = (count($previousRetakesEntities)+1);

            //duplicate $marksheetEntity, update data in duplicated and presist
            $marksheetWithRetake = clone $marksheetEntity;

            $marksheetWithRetake->setGroupWork($formData['groupWork']);
            $marksheetWithRetake->setAssignment($formData['assignment']);
            $marksheetWithRetake->setQuiz($formData['quiz']);
            $marksheetWithRetake->setAttendance($formData['attendance']);
            $marksheetWithRetake->setFinalExam($formData['finalExam']);

            $totalMarks = ($formData['groupWork']+$formData['assignment']+$formData['quiz']+$formData['attendance']+$formData['finalExam']);
            $marksheetWithRetake->setTotalMarks($totalMarks);

            //getting user grade based on total marks ///////////
            $gradingSystemEntity = $em->createQuery("SELECT gs FROM ModelBundle:GradingSystem gs WHERE :totalMarks BETWEEN gs.marksMin AND gs.marksMax")->setParameter('totalMarks', $totalMarks)->setMaxResults(1)->getOneOrNullResult();
            //echo $gradingSystemEntity->getGradeLetter();

            $marksheetWithRetake->setGrade($gradingSystemEntity->getGradeLetter());//////////
            $marksheetWithRetake->setPoints($gradingSystemEntity->getEarnedPoints());////////////
            $marksheetWithRetake->setSubjectTotalPoints(($marksheetEntity->getCreditHours()*$gradingSystemEntity->getEarnedPoints()));////////////

            //TOBE CHECKED:
            //Final Exam Marks Criteria * 30% / 100 = 12
            $finalExam30Percent = $formData['finalExamMarksCriteria']*30/100;
            if( ($formData['finalExam']>$finalExam30Percent) AND ($totalMarks>59) ){ //59 came from "Grading System Table, which is fail"
                $marksheetWithRetake->setRemarks("Pass");
            }else{
                $marksheetWithRetake->setRemarks("Fail");
            }

            $marksheetWithRetake->setIsRetakeEnabled(null); //re-takes cannot have "isRetakeEnabled set to 1"
            $marksheetWithRetake->setRetakeNumber($newRetakeNumber); //because each new re-take will have number for identification

            $marksheetWithRetake->setIsCheckedByDH(null);
            $marksheetWithRetake->setIsCheckedByTOM(null);
            $marksheetWithRetake->setIsCheckedBySAD(null);
            $marksheetWithRetake->setIsCheckedByHR(null);
            $marksheetWithRetake->setIsReleasedForStudent(null);

            $marksheetWithRetake->setDateCreated(new \DateTime());
            $marksheetWithRetake->setDateUpdated(new \DateTime());

            $em->persist($marksheetWithRetake);

            //disabling re-take from marksheet entity which had it previously so teacher won't see there is re-take for same user again, unless it's open again by tom
            $marksheetEntity->setIsRetakeEnabled(null);

            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 're-Take was successfully submitted');
            return $this->redirect(
                $this->generateUrl('wit_teacher_course_retakes_1', 
                    array('course_id'=>$marksheetWithRetake->getCourseId())
                )
            );

            exit;
        }

        return array(
            'enrolmentData' => $enrolmentData,
            'marksheets' => $marksheets,
            'criteria' => $gradingCriteriaEntity,
        );
    }

}