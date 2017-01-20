<?php

namespace Wit\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use Wit\ModelBundle\Entity\User;
use Wit\ModelBundle\Entity\UserLogs;

use Wit\UserBundle\Form\Model\ChangePassword;
use Wit\UserBundle\Form\ChangePasswordType;

use Wit\ModelBundle\Entity\AdmissionScheduler;

use Wit\ModelBundle\Entity\Application;

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
    	//to populate user notification logs in user dashboard right side block
        /*
        if ( $this->get('security.context')->getToken()->getUser()->getLogs() ){
            $userLogs = $this->get('security.context')->getToken()->getUser()->getLogs();
        }else{
            $userLogs = null;
        }
        */

        $onlineUserId = $this->get('security.context')->getToken()->getUser()->getId();
        //$onlineUserRole = $this->get('security.context')->getToken()->getUser()->getRoles()[0];

        //$onlineUserCreatedDate = $this->get('security.context')->getToken()->getUser()->getDateCreated();

        $em = $this->getDoctrine()->getManager();
        //$sadLogs = $em->getRepository('ModelBundle:UserLogs')->findBy(array('isForUser' => 1), array('id'=>'DESC'), 10);

        /*
            getting those user logs which are marked isForUser so display to all users
            and those logs which are marked userRoleUserId so display to that user only.
        */
        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->orWhere($criteria->expr()->contains('isForUser', 1))
                 ->orWhere($criteria->expr()->contains('userRoleUserId', $onlineUserId))
                 ->orderBy(array("id" => $criteria::DESC))
                 ->setFirstResult(0)
                 ->setMaxResults(10);
        $sadLogs = $em->getRepository('ModelBundle:UserLogs')->matching($criteria);
        if (!$sadLogs) {
            //throw $this->createNotFoundException('Unable to find User Logs entity.');
            $sadLogs = null;
        }

        $onlineUserRole = $this->get('security.context')->getToken()->getUser()->getRoles()[0];
        $adminQuickNews = $em->getRepository('ModelBundle:QuickNews')->findBy(array('toUserRole'=>trim($onlineUserRole)), array('id'=>'DESC'), 5);

        //checking if user has submitted application against current open schedule
        //if already submitted application he won't see popup for admission!
        $userApplicationInCurrentSchedule = $em->getRepository('ModelBundle:User')->findUserApplicationInCurrentSchedule($this->get('wit.helper.user')->getOnlineUser());
        
        if( $userApplicationInCurrentSchedule instanceof Application ){ //because it's returning Application if user has one
            $alreadySubmittedApplicationId = $userApplicationInCurrentSchedule->getId();
        }else{
            $alreadySubmittedApplicationId = null;
        }

        //checking if admission is open to display popup...
        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->orWhere($criteria->expr()->contains('isOpen', 1))
                 ->setMaxResults(1);
        $admissionScheduler = $em->getRepository('ModelBundle:AdmissionScheduler')->matching($criteria);

        if (!$admissionScheduler OR !empty($alreadySubmittedApplicationId) ) { //pop should not display to user if user already submitted an application against current open schedule
            $admissionScheduler = null;
        }

        return array(
            'userLogs' => $sadLogs,
            'quickNews' => $adminQuickNews,
            'admissionScheduler' => $admissionScheduler,
        );
    }

    /**
     * @Route("/register")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        if ($request->isMethod('POST')){

            $firstname  = $this->get('request')->request->get('firstname');
            $lastname   = $this->get('request')->request->get('lastname');
            $email      = $this->get('request')->request->get('email');
            //$nationalid = $this->get('request')->request->get('nationalid');
            $mobile     = $this->get('request')->request->get('mobile');
            $password   = $this->get('request')->request->get('password');

            $em = $this->getDoctrine()->getManager();
            
            $user = new User();
            
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setUsername($email);
            $user->setEmail($email);
            $user->setMobile($mobile);
            //$user->setNationalid($nationalid);
            $user->setIsActive(1);

            $user->setDateCreated(new \DateTime()); // updating date created..
            
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            
            /*
             * Setting User ROle
             * getting user role instance from role entity to apply it to registering user
             * 4 = ROLE_USER in DB
             */
            $roleUser = $em->getRepository('ModelBundle:Role')->find(4);
            $user->addRole($roleUser);

            $em->persist($user);
            $em->flush();

            if( $user->getId() ){

                /*
                 * Sending account created email
                 *
                 * */

                //validate email address
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    
                    $fullname = $firstname.' '.$lastname;

                    $subject = "Forwarded - Your New Account at (WIT)";

                    $messageBody = "<strong>Dear ".$fullname.',</strong><br />';
                    $messageBody .= "<br />Your new account has been created successfully at http://wit.edu.sa and is ready to use, please use following details to login to your account:";
                    $messageBody .= "<p><strong>EMAIL:</strong> <br />".$email."<br /><br />";
                    $messageBody .= "<strong>PASSWORD:</strong> <br />".$password."<br /><br />";
                    $messageBody .= "<strong>LOGIN URL:</strong> <br />http://www.wit.edu.sa/</p><br />";
                    $messageBody .= "Please feel free to email us at 'support@wit.edu.sa' to post any feedback/ suggestion or to get our support.<br /><br />";
                    $messageBody .= "<strong>Best Regards,</strong><br />";
                    $messageBody .= "Al Watania Poultry Institute Of Technology (WIT)<br />";
                    $messageBody .= "P.O.Box: 111, Al Bukayriah 51941<br />Al Qassim, K.S.A";

                    $message = \Swift_Message::newInstance()
                        ->setSubject($subject)
                        ->setFrom('noreply@wit.edu.sa')
                        ->setTo(array($email => $fullname))
                        ->setBody($messageBody,'text/html');

                    $this->get('mailer')->send($message);

                } else {
                    //not valid email address
                }

                /*
                 * Account was created and notification was sent..
                 * Redirect user to login page
                 */
                $this->get('session')->getFlashBag()->set('error', 'Your new account has been created successfully and is ready to use!');
                return $this->redirect($this->generateUrl('wit_auth_security_login'));
            }else{
                /*
                 * Account was't created..
                 * redirect user back to register page with error
                 */
                $this->get('session')->getFlashBag()->set('error', 'Your Account was not created! Please try again later..');
                return $this->redirect($this->generateUrl('wit_user_default_register'));
            }
        }
    }
    
    /**
     * @Route("/change-password")
     * @Template()
     */
    public function changePasswordAction(Request $request)
    {   
        $changePasswordModel = new ChangePassword();
        
        $form = $this->createForm(new ChangePasswordType(), $changePasswordModel, array(
            'action' => $this->generateUrl('wit_user_default_changepassword'),
            'method' => 'POST',
            'attr' => array(
                'id'    => 'change-password-form',
            )
        ));
        
        $form->handleRequest($request);
        
        //if ($form->isSubmitted() && $form->isValid()) {
        if ( $form->isSubmitted() ) {
            if ( $form->isValid() ) {
                $data = $form->getData();
                
                $new_pwd = $data->getNewPassword();
                
                $user = $this->getUser();
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                $new_pwd_encoded = $encoder->encodePassword($new_pwd, $user->getSalt());
                $user->setPassword($new_pwd_encoded);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                //TODO: User password change log should be created..
                
                $this->get('session')->getFlashBag()->set('error', 'Your password was successfully changed..');
                return $this->redirect($this->generateUrl('wit_user_default_changepassword'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * will be called by JQuery Validation plugin for email exist verification
     *
     * @Route("/check-email-if-already-exist")
     * @Template()
     * TODO: server side verification should also be done..
     */
    public function checkEmailIfAlreadyExistAction(Request $request){
        if ($request->isMethod('POST')){
            $email = $request->request->all()['email'];
            if( $this->checkUserEmailInDB($email) == true ){
                //email already exist in db, cannot register
                echo "false";
            }else{
                //email does't exist in db, user can register
                echo "true";
            }
        }
        exit;
    }

    /**
     * will be called by JQuery Validation plugin for saudi id validation
     *
     * @Route("/validate-saudi-id")
     * @Template()
     * TODO: server side verification should also be done..
     */
    public function validateSaudiNationalIdAction(Request $request){
        if ($request->isMethod('POST')){
            $nid = $request->request->all()['nationalid'];
            /* TRUE if valid saudi id */
            if( $this->saudiIdvalidator($nid)>0 ){
                /* TRUE if nation id already exist in DB */
                if($this->checkUserNationalIdInDB($nid)==true){
                    //valid id but already exist in db
                    echo 'false';
                }else{
                    //valid id and not exist in db so user can register
                    echo 'true';
                }
            }else{
                //echo "not valid saudi id";
                echo 'false';
            }
        }
        exit;
    }

    /*
        This function will validate check if provided id is a valid saudi id or not..
        Param: id = saudi id
    */
    private function saudiIdvalidator($id){
        $id = trim($id);
        if(!is_numeric($id)) return false;
        if(strlen($id) !== 10) return false;
        $type = substr ( $id, 0, 1 );
        if($type != 2 && $type != 1 ) return false;
    
        for( $i = 0 ; $i<10 ; $i++ ) {
            if ( $i % 2 == 0){
                $ZFOdd = str_pad ( ( substr($id, $i, 1) * 2 ), 2, "0", STR_PAD_LEFT );
                @$sum += substr ( $ZFOdd, 0, 1 ) + substr ( $ZFOdd, 1, 1 );
            }else{
                $sum += substr ( $id, $i, 1 );
            }
        }
        return $sum%10 ? false : $type;
    }

    private function checkUserEmailInDB($email) {
        $em = $this->getDoctrine()->getManager();
        $result = $em->createQuery('SELECT u.email FROM ModelBundle:User u WHERE u.email = :email')
                     ->setParameters(array('email'=>$email))
                     ->getOneOrNullResult();
        if($result['email']){return true;}else{return false;}
    }

    /* returns true if national id already exist in db */
    private function checkUserNationalIdInDB($id) {
        $em = $this->getDoctrine()->getManager();
        $result = $em->createQuery('SELECT u.nationalid FROM ModelBundle:User u WHERE u.nationalid = :nationalid')
                     ->setParameters(array('nationalid'=>$id))
                     ->getOneOrNullResult();
        if($result['nationalid']){return true;}else{return false;}
    }
    
    
}
