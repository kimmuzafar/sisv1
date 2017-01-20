<?php

namespace Wit\OnboardingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use Wit\ModelBundle\Entity\User;

/**
 * Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ModelBundle:User')->findBy(array(), array('id' => 'DESC'));
        
        return array(
            'users' => $users,
        );
    }
    
    /**
     * This will onboard individual users
     * 
     * @Route("/individual/user/{user_id}")
     * 
     * @Template()
     */
    public function individualAction($user_id)
    {
        $em = $this->getDoctrine()->getManager();
        
        //selected user by user...
        $userEntity = $em->getRepository('ModelBundle:User')->find($user_id);
        
        //ROLE_USER
        $userRole = $em->getRepository('ModelBundle:Role')->find(4);
        
        //ROLE_STUDENT
        $studentRole = $em->getRepository('ModelBundle:Role')->find(6);
        
        $userEntity->removeRole($userRole); //remove current user role
        $userEntity->setRoles($studentRole); //changing user role to ROLE_STUDENT

        $em->persist($userEntity);
        $em->flush();

        $this->get('session')->getFlashBag()->set('error', "User '".$userEntity->getFirstname()." ".$userEntity->getLastname()."' was onboarded successfully.");
        return $this->redirect($this->generateUrl('wit_onboarding_default_index'));
    }
    
    /**
     * @Route("/by-group")
     * @Template()
     */
    public function byGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($request->isMethod('POST')){

            $wit_id_last_used_pattern = $this->get('request')->request->get('wit_id_last_used_pattern');
            
            $selectedGroup = $this->get('request')->request->get('group');
            
            //getting instance of selected group
            $groupInstance = $em->getRepository('ModelBundle:ApplicationGroup')->find($selectedGroup);

            //getting all applications in selected group
            foreach ($groupInstance->getGroupApplications() as $index=>$agroup){

                $NEW_WIT_ID = ($wit_id_last_used_pattern+$index+1);
                // echo $NEW_WIT_ID."<br />";
                // continue;
                // exit;

                $user = $agroup->getApplication()->getUser(); //object
                $userRole = $user->getRoles()[0]; //object
                
                //getting only those users whose role is ROLE_USER
                //why this because some users may already converted into ROLE_STUDENT and still exist in the group
                if ($userRole == "ROLE_USER"){
                    
                    //removing current user role ROLE_USER to assign him another role
                    $user->removeRole($userRole);
                    
                    //getting Student Role instance from ROLE Entity so we can convert this user into Student
                    $studentRole = $em->getRepository('ModelBundle:Role')->find(6);
                    
                    //set user role to student
                    $user->setRoles($studentRole);
                    $user->setTraineeid($NEW_WIT_ID); //setting trainee id
                    
                    $em->persist($user);
                    $em->flush();

                    //to be seved in pattern history table for next onboarding
                    $patternHistoryEntity = $em->getRepository('ModelBundle:PatternHistory')->find(1);
                    $patternHistoryEntity->setPattern($NEW_WIT_ID);
                    $em->flush();
                    
                } //end if   
            } // end foreach
            
            $this->get('session')->getFlashBag()->set('error', 'All Users in selected group were successfully Onboarded as Student in the System..');
            return $this->redirect($this->generateUrl('wit_onboarding_default_bygroup'));
            
        }// end: if post check
        
        //Application group entity to populate group dropdown
        $groups = $em->getRepository('ModelBundle:ApplicationGroup')->findBy(array(), array('id' => 'DESC'));

        //getting onboarding last updated pattern
        $patternHistoryEntity = $em->getRepository('ModelBundle:PatternHistory')->find(1);
        
        return array(
            'groups' => $groups,
            'lastWitIdPattern'=>$patternHistoryEntity,
        );
    }
}
