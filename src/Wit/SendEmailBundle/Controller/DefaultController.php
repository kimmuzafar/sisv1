<?php

namespace Wit\SendEmailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use Wit\ModelBundle\Entity\SendEmail;

/**
 * Send Email controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     *
     * This action will display all history of all send emails
     *
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sentEmailsEntities = $em->getRepository('ModelBundle:SendEmail')->findBy(array(), array('id' => 'DESC'));

        return array(
            'sentEmails' => $sentEmailsEntities,
        );
    }

    /**
     *
     * Sending email to application group
     *
     * @Route("/to/app-group")
     * @Template()
     */
    public function appGroupAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

    	if ($request->isMethod('POST')){

    		$subject = 		$this->get('request')->request->get('subject');
    		$messageBody = 	$this->get('request')->request->get('message');
    		$groupid = 		$this->get('request')->request->get('group');

    		$message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom('noreply@wit.edu.sa')
                    //->setTo($emails)
                    //->setBcc(array("muzafars@outlook.com"=>"Muzafar", "kimmuzafars@gmail.com"=>"Muzafar"))
                    //->setBcc($emails)
            		->setBody($messageBody,'text/html');

    		//getting instance of user selected application group
            $groupInstance = $em->getRepository('ModelBundle:ApplicationGroup')->find($groupid);
            
            $failedRecipients = array();
			$numSent = 0;
			$recipients = array();

            foreach ($groupInstance->getGroupApplications() as $groupApplication) {
            	$email = $groupApplication->getApplication()->getEmail();
            	$firstname = $groupApplication->getApplication()->getFirstname();
            	//validate email address
            	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
					$message->setTo(array($email=>$firstname));
					$recipients[] = $email;
				} else {
					//not valid email address
				}
				$numSent += $this->get('mailer')->send($message, $failedRecipients);
            }

            //saving to make history of sent emails
            $sendEmailEntity = new SendEmail();
            $sendEmailEntity->setSubject($subject);
            $sendEmailEntity->setMessage($messageBody);
            $sendEmailEntity->setRecipients(implode(",", $recipients));
            $sendEmailEntity->setSender($this->getUser()->getFirstname().' '.$this->getUser()->getLastname());
            $em->persist($sendEmailEntity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', '('.$numSent.') Emails were successfully sent!');
            return $this->redirect($this->generateUrl('wit_sendemail_default_appgroup'));
            
    	}

    	//Application group entity to populate group dropdown
        $groups = $em->getRepository('ModelBundle:ApplicationGroup')->findBy(array(), array('id' => 'DESC'));
        
        return array(
            'groups' => $groups,
        );
    }

    /**
     *
     * Sending email to user group
     *
     * @Route("/to/user-group")
     * @Template()
     */
    public function userGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

    	if ($request->isMethod('POST')){

    		$subject = 		$this->get('request')->request->get('subject');
    		$messageBody = 	$this->get('request')->request->get('message');
    		$groupid = 		$this->get('request')->request->get('group');

    		$message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom('noreply@wit.edu.sa')
                    //->setTo($emails)
                    //->setBcc(array("muzafars@outlook.com"=>"Muzafar", "kimmuzafars@gmail.com"=>"Muzafar"))
                    //->setBcc($emails)
            		->setBody($messageBody,'text/html');

    		//getting instance of user selected user group
            $groupInstance = $em->getRepository('ModelBundle:UserGroup')->find($groupid);
            
            $failedRecipients = array();
			$numSent = 0;
			$recipients = array();

            foreach ($groupInstance->getGroupUsers() as $groupUser) {
            	$email = $groupUser->getUser()->getEmail();
            	$firstname = $groupUser->getUser()->getFirstname();
            	//validate email address
            	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
					$message->setTo(array($email=>$firstname));
					$recipients[] = $email;
				} else {
					//not valid email address
				}
				$numSent += $this->get('mailer')->send($message, $failedRecipients);
            }

            //saving to make history of sent emails
            $sendEmailEntity = new SendEmail();
            $sendEmailEntity->setSubject($subject);
            $sendEmailEntity->setMessage($messageBody);
            $sendEmailEntity->setRecipients(implode(",", $recipients));
            $sendEmailEntity->setSender($this->getUser()->getFirstname().' '.$this->getUser()->getLastname());
            $em->persist($sendEmailEntity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', '('.$numSent.') Emails were successfully sent!');
            return $this->redirect($this->generateUrl('wit_sendemail_default_usergroup'));
            
    	}

    	//User group entity to populate group dropdown
        $groups = $em->getRepository('ModelBundle:UserGroup')->findBy(array(), array('id' => 'DESC'));
        
        return array(
            'groups' => $groups,
        );
    }

    /**
     *
     * view single email from history
     *
     * @Route("/view/{emailid}")
     * @Template()
     */
    public function viewAction($emailid)
    {
    	$em = $this->getDoctrine()->getManager();
        $emailEntity = $em->getRepository('ModelBundle:SendEmail')->find($emailid);
        $recipients = explode(',', $emailEntity->getRecipients());
        return array(
        	"emailEntity"=>$emailEntity,
        	"recipients"=>$recipients,
        );
    }
}
