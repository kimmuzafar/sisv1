<?php

namespace Wit\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use Wit\ModelBundle\Entity\User;
use Wit\ModelBundle\Entity\UserLogs;

/**
 * Login controller
 * @return Response
 * @Route("/security")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'AuthBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/login_check")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/login-redirect")
     * 
     * Bundle based redirection being done from here... once login this action will be called..
     */
    public function loginRedirectAction()
    {
        // this action will be called from security.yml to redirect users based on role
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // the user has the ROLE_ADMIN role, so act accordingly
            return $this->redirect($this->generateUrl('wit_admin_default_index'));

        }else if ($this->get('security.context')->isGranted('ROLE_TOD')) {
            // the user has the ROLE_TOD role, so act accordingly
            return $this->redirect($this->generateUrl('wit_tod_default_index'));

        }else if ($this->get('security.context')->isGranted('ROLE_DH')) {
            // the user has the ROLE_DH role, so act accordingly
            return $this->redirect($this->generateUrl('wit_dh_default_index'));

        }else if ($this->get('security.context')->isGranted('ROLE_SAD')) {
            // the user has the ROLE_SAD role, so act accordingly
            return $this->redirect($this->generateUrl('wit_sad_default_index'));

        }else if ($this->get('security.context')->isGranted('ROLE_TEACHER')) {
            // the user has the ROLE_TEACHER role, so act accordingly
            return $this->redirect($this->generateUrl('wit_teacher_default_index'));

        }else if ($this->get('security.context')->isGranted('ROLE_USER')) {
            // the user has the ROLE_USER role, so act accordingly
            return $this->redirect($this->generateUrl('wit_user_default_index'));

        }else if ($this->get('security.context')->isGranted('ROLE_STUDENT')) {
            // the user has the ROLE_STUDENT role, so act accordingly
            return $this->redirect($this->generateUrl('wit_student_default_index'));

        }else{
            // un recognized role, user should be redirected to login page
            return $this->redirect($this->generateUrl('wit_auth_security_login'));
        }
    }

    /**
     * Logout Admin
     *
     * @Route("/logout")
     *
     * */
    public function logoutAction(){

    }

    /**
     * @Route("/test")
     * @Template()
     */
    public function testAction()
    {
        /*
         * Sending account created email
         *
         * */
        $message = \Swift_Message::newInstance()
                ->setSubject("WIT: Your New Account")
                ->setFrom('noreply@wit.edu.sa')
                ->setTo(array("mali@wit.edu.sa" => "Muzafar ALi jatoi"))
        ->setBody(
                $this->renderView(
                    'AuthBundle:includes:emails/new-account-created.html.twig',
                    array(
                        'name' => "Muzafar Ali Jatoi",
                        'username' => "kimmuzafar",
                        'password' => "pass11234",
                        'date' => date('d-m-Y H:i:s'),
                    )
                ),
                'text/html'
        );
        $this->get('mailer')->send($message);

        exit;
    }

}