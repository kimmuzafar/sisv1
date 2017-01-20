<?php

namespace Wit\UserEnrolmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;

use Wit\ModelBundle\Entity\CourseBatch;
use Wit\SettingBundle\Form\CoursebatchType;

use Wit\ModelBundle\Entity\Enrolment;

/**
 * Enrol Users controller.
 * 
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * This action will serve as sole action for Add/ Edit and Delete record
     * 
     * @Route("/in/{id}")
     * 
     * @Template()
     */
    public function indexAction($id) /* $id = course id */
    {
        /* TODO: (edited for this todo but to be checked again) all users are not display, this should be corrected so all users will display */

        $em = $this->getDoctrine()->getManager();
        //$users = $em->getRepository('ModelBundle:User')->findBy(array(), array('id' => 'DESC'));

        //getting only these users who has student role
        //$users = $em->getRepository('ModelBundle:Role')->findBy(array('role'=>'ROLE_STUDENT'), array('id' => 'DESC'));
        $users = $em->getRepository('ModelBundle:User')->findBy(array(), array('id' => 'DESC'));

        //getting all enrolments for the course in param
        $enrolments = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$id), array('id' => 'DESC'));

        //sending course id from url to view
        $params = array(
            "id" => $id
        );

        //we are getting enrolled users through enrolment entity because we don't need to display enrolled users in view
        $enrolledUserIds = array();
        foreach ($enrolments as $enrol) {
            $enrolledUserIds[] = $enrol->getUser()->getId();
        }

        return array(
            //'users' => $users[0]->getUsers(),
            'users' => $users,
            'course' => $params,
            'enrolments' => $enrolments,
            'enrolledUserIds' => $enrolledUserIds, //enrolled users id's
        );

        //$query = $em->createQuery('SELECT u FROM ModelBundle:User u')->getResult();

        /*
        $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u, r')
            ->from('CRMCoreBundle:User', 'u')
            ->innerJoin('u.profiles','p')
            ->innerJoin('p.routegroups','rg')
            ->innerJoin('rg.routes','r')
            ->where('u.id = :user_id')->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getResult();
        */

        /*
        $nnn = $em->createQueryBuilder()
            ->select('r')
            ->from('ModelBundle:Role', 'r')
            ->innerJoin('r.users','u')
            ->innerJoin('ModelBundle:Enrolment','e')
            ->where('r.role = :user_role')->setParameter('user_role', 'ROLE_STUDENT')
            ->andWhere('e.user = :user_role')->setParameter('user_role', 'ROLE_STUDENT')
            ->getQuery()
            ->getResult();
        */

        /*

        $studentUsers = $em->createQueryBuilder()
                        ->select('u')
                        ->from('ModelBundle:User', 'u')
                        ->leftJoin('ModelBundle:Role', 'r')
                        ->innerJoin('r.users', 'ru')
                        ->where('ru.id = u.id')
                        ->getQuery()
                        ->getResult();
        */

        


        /*
                    'SELECT p, c FROM AppBundle:Product p
                    JOIN p.category c
                    WHERE p.id = :id'
                )->setParameter('id', $id);



        $q = $em->createQueryBuilder('p')
            ->leftJoin('p.adresses', 'a')
            ->leftJoin('a.ville', 'v')
            ->where('v.cp=:cp')
            ->setParameter('cp', $cp);



        $query = $em->createQuery(
                "SELECT "
            );


        $query = $em->createQuery(
                    'SELECT p
                    FROM AppBundle:Product p
                    WHERE p.price > :price
                    ORDER BY p.price ASC'
                )->setParameter('price', '19.99');
        */
    }

    /**
     * This will enrol selected user in perticular course
     * 
     * @Route("/user/{user_id}/in/{course_id}")
     * 
     * @Template()
     */
    public function enrolAction($course_id, $user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('ModelBundle:Course')->find($course_id);
        $user = $em->getRepository('ModelBundle:User')->find($user_id);

        $newEnrolment = new Enrolment();

        $newEnrolment->setCourse($course);
        $newEnrolment->setUser($user);
        $newEnrolment->setDateCreated(new \DateTime());

        $em->persist($newEnrolment);
        $em->flush();

        $this->get('session')->getFlashBag()->set('error', 'User was enrolled successfully.');
        return $this->redirect($this->generateUrl('wit_userenrolment_default_index', array('id'=>$course_id)));
    }

    /**
     * This will un-enrol selected user in perticular course
     * 
     * @Route("/unenrol/{enrolment_id}/from/{course_id}")
     * 
     * @Template()
     */
    public function unenrolAction($enrolment_id, $course_id)
    {
        if($enrolment_id){

            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('ModelBundle:Enrolment')->find($enrolment_id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Enrolment entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'User was un-enrolled successfully.');
            return $this->redirect($this->generateUrl('wit_userenrolment_default_users', array('id'=>$course_id)));
        }
    }

    /**
     * This action will display all users enrolled in current course
     * 
     * @Route("/users/in/{id}")
     * 
     * @Template()
     */
    public function usersAction($id) /* $id = course id */
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('ModelBundle:Course')->find($id);

        $enrolmentData = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$course), array('id' => 'DESC'));

        return array(
            'enrolmentData' => $enrolmentData,
        );
    }
    
    /*
    public function enrolAction($course_id, $user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('ModelBundle:Course')->find($course_id);
        $user = $em->getRepository('ModelBundle:User')->find($user_id);

        $newEnrolment = new Enrolment();

        $newEnrolment->setCourse($course);
        $newEnrolment->setUser($user);
        $newEnrolment->setDateCreated(new \DateTime());

        $em->persist($newEnrolment);
        $em->flush();

        $this->get('session')->getFlashBag()->set('error', 'User was enrolled successfully.');
        return $this->redirect($this->generateUrl('wit_userenrolment_default_index', array('id'=>$course_id)));
    }*/
    
    /**
     * This action will display user enrolment form
     * From this action any user having student / teacher role can be enrolled in any subject/course
     * 
     * @Route("/new")
     * @Route("/new/{$course}")
     * 
     * @Template()
     */
    public function newAction($course=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('ModelBundle:Role')->findBy(array('role'=>'ROLE_STUDENT'), array('id' => 'DESC'));   
        $courses = $em->getRepository('ModelBundle:Course')->findBy(array(), array('id' => 'DESC'));
        
        //getting all enrolments for the course in param
        $enrolments = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$course), array('id' => 'DESC'));
        
        //we are getting enrolled users through enrolment entity because we don't need to display enrolled users in view
        $enrolledUserIds = array();
        foreach ($enrolments as $enrol) {
            $enrolledUserIds[] = $enrol->getUser()->getId();
        }
        
        //sending course id from url to view
        $params = array(
            "id" => $course
        );
        
        return array(
            'courses'=>$courses,
            'course' => $params,
            'users'=>$users,
            'enrolledUserIds' => $enrolledUserIds, //enrolled users id's
        );
    }

    /**
     * This action will allow authorized users to enrol users in courses quarter-wise
     * From this action any user having student role can be enrolled in any quarter
     * 
     * @Route("/quarter")
     * 
     * @Template()
     */
    public function quarterAction(Request $request, $course=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($request->isMethod('POST')){

            $selectedGroup = $this->get('request')->request->get('group');
            $selectedQuarter = $this->get('request')->request->get('quarter');

            //getting all courses for selected quarter
            $courses = $em->getRepository('ModelBundle:Course')->findBy(array('coursequarter'=>$selectedQuarter), array('id' => 'ASC'));

            //getting all group user which group matches user selected group so we can get users from it
            $groupsAndUsers = $em->getRepository('ModelBundle:GroupUser')->findBy(array('userGroup'=>$selectedGroup), array('id' => 'ASC'));

            //courses users to be enrolled in
            $coursesArray = array();

            //users to be enrolled in courses
            $usersArray = array();

            foreach ($courses as $course) {
                $coursesArray[] = $course->getId();
            }

            foreach ($groupsAndUsers as $groupAndUser) {
                $usersArray[] = $groupAndUser->getUser()->getId();
            }

            //create log of users who are already enrolled in selected course and skipped on repeated enrollment
            $alreadyEnrolledUsers = array();

            //iterate through courses array to enroll users
            //no user will be enrolled if he is already enrolled in the course
            foreach ($coursesArray as $course) {
                /*
                echo "<br />";
                echo "-------";
                echo $course;
                echo "-------";
                echo "<br />";
                */

                $courseInstance = $em->getRepository('ModelBundle:Course')->find($course);

                //iterate through users to be enrolled in current course in loop
                foreach ($usersArray as $user) {

                    $userInstance = $em->getRepository('ModelBundle:User')->find($user);

                    //$userEnrolment = $em->createQuery("SELECT a FROM ModelBundle:Enrolment a WHERE a.course=:course AND a.user=:user")->setParameter('currenttime', date('Y-m-d h:i:s'))->setMaxResults(1)->getOneOrNullResult();
                    $userEnrolment = $em->createQuery("SELECT a FROM ModelBundle:Enrolment a WHERE a.course=".$course." AND a.user=".$user)->setMaxResults(1)->getOneOrNullResult();
                    if($userEnrolment){
                        //echo $user." - already enrolled<br />";
                        $alreadyEnrolledUsers[] = $userInstance->getTraineeid();
                        continue; //skip this iteration as this user is already enrolled
                    }else{
                        //user not enrolled in given course proceed to his enrolment
                        //echo $user." - enroll him..";

                        $newEnrolment = new Enrolment();

                        $newEnrolment->setCourse($courseInstance);
                        $newEnrolment->setUser($userInstance);
                        $newEnrolment->setDateCreated(new \DateTime());

                        $em->persist($newEnrolment);

                    }

                }//end user foreach

            }//end course foreach

            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'All users in selected group were successfully enrolled in selected quarter..');
            return $this->redirect($this->generateUrl('wit_userenrolment_default_quarter'));

            //$user = $em->getRepository('ModelBundle:User')->find($user_id);

            //dump($coursesArray);
            //dump($usersArray);

            /*
            echo $selectedGroup;
            echo "<br />";
            echo $selectedQuarter;
            exit;
            */
            exit;
        }else{

            //Application group entity to populate group dropdown
            $groups = $em->getRepository('ModelBundle:UserGroup')->findBy(array(), array('id' => 'DESC'));

            //course quarters entity to populate quarter dropdown
            $quarters = $em->getRepository('ModelBundle:CourseQuarter')->findBy(array(), array('id' => 'ASC'));
            
            return array(
                'groups' => $groups,
                'quarters' => $quarters,
            );
        }
    }

    /*
    public function enrolAction($course_id, $user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('ModelBundle:Course')->find($course_id);
        $user = $em->getRepository('ModelBundle:User')->find($user_id);

        $newEnrolment = new Enrolment();

        $newEnrolment->setCourse($course);
        $newEnrolment->setUser($user);
        $newEnrolment->setDateCreated(new \DateTime());

        $em->persist($newEnrolment);
        $em->flush();

        $this->get('session')->getFlashBag()->set('error', 'User was enrolled successfully.');
        return $this->redirect($this->generateUrl('wit_userenrolment_default_index', array('id'=>$course_id)));
    }
    */
    
    /**
     * This action will display list of all currently enrolled students/trainees in any subject/course
     * This is useful to identify current active trainees
     * 
     * @Route("/view-trainees")
     * 
     * @Template()
     */
    public function viewTraineesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $enrolmentEntities = $em->getRepository('ModelBundle:Enrolment')->findBy(array(), array('id' => 'DESC'));
        
        return array(
            'enrolmentEntities' => $enrolmentEntities,
        );
    }
    
    /**
     * This action will display list of all currently enrolled teachers in any subject/course
     * This is useful to identify current active teachers
     * 
     * @Route("/view-teachers")
     * 
     * @Template()
     */
    public function viewTeachersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $enrolmentEntities = $em->getRepository('ModelBundle:Enrolment')->findBy(array(), array('id' => 'DESC'));
        
        return array(
            'enrolmentEntities' => $enrolmentEntities,
        );
    }

}
