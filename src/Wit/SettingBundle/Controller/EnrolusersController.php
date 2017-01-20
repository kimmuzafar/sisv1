<?php

namespace Wit\SettingBundle\Controller;

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
 * @Route("/enrol-users")
 */
class EnrolusersController extends Controller
{
    /**
     * This action will serve as sole action for Add/ Edit and Delete record
     * 
     * @Route("/course/{id}")
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

        //to send course id from url to view
        $params = array(
            "id" => $id
        );

        return array(
            //'users' => $users[0]->getUsers(),
            'users' => $users,
            'course' => $params,
            'enrolments' => $enrolments,
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
     * @Route("/course/{course_id}/user/{user_id}")
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
        return $this->redirect($this->generateUrl('wit_setting_enrolusers_index', array('id'=>$course_id)));
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
            return $this->redirect($this->generateUrl('wit_setting_enrolusers_enrolled', array('id'=>$course_id)));
        }
    }

    /**
     * This action will display all users enrolled in current course
     * 
     * @Route("/enrolled/c/{id}")
     * 
     * @Template()
     */
    public function enrolledAction($id) /* $id = course id */
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('ModelBundle:Course')->find($id);

        $enrolmentData = $em->getRepository('ModelBundle:Enrolment')->findBy(array('course'=>$course), array('id' => 'DESC'));

        return array(
            'enrolmentData' => $enrolmentData,
        );
    }

}