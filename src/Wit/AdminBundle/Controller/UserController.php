<?php

namespace Wit\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use Wit\ModelBundle\Entity\Role;
use Wit\ModelBundle\Entity\User;

use Wit\AdminBundle\Form\UserType;
use Wit\AdminBundle\Form\SearchUserType;

use Doctrine\Common\Collections\Criteria;

use Wit\SettingBundle\Form\RoleType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * This action will serve as sole action for Add/ Edit and Delete Users
     * 
     * @Route("/")
     * @Route("/edit/{id}", defaults={"id" = null})
     * 
     * @Template()
     */
    public function indexAction($id=null)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ModelBundle:User')->findBy(array(), array('id' => 'DESC'));

        //form to create a User
        $addUserform = $this->createForm(new UserType(), new User(), array(
            'action' => $this->generateUrl('wit_admin_user_new'),
            'method' => 'POST',
            'attr' => array(
                'id'    => 'add-new-user-form',
            )
        ));

        //to populate user gorup creation dropdown
        $userGroups = $em->getRepository('ModelBundle:UserGroup')->findBy(array(), array('id' => 'DESC'));

        $searchForm = $this->createSearchForm();

        //if user wants to edit a record
        if($id){
            $userEntity = $em->getRepository('ModelBundle:User')->find($id);
            if (!$userEntity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $editUserForm = $this->createForm(new UserType(), $userEntity, array(
                'action' => $this->generateUrl('wit_admin_user_update', array('id' => $userEntity->getId())),
                'method' => 'PUT',
                'attr'   => array(
                    'id' => 'update-user-form',
                    'novalidate' => 'novalidate',
                )
            ))->remove('password'); //this will remove password and roles fields from form before submitting

            return array(
                'users'         => $users,
                'addUserForm'   => $addUserform->createView(),
                'userEntity'    => $userEntity,
                'editUserForm'  => $editUserForm->createView(),
                'searchform'   => $searchForm->createView(),
                'userGroups' => $userGroups,
            );
        }else{
            return array(
                'users'         => $users,
                'addUserForm'   => $addUserform->createView(),
                'searchform'   => $searchForm->createView(),
                'userGroups' => $userGroups,
            );
        } 
    }

    /**
     * Create a new user entity.
     *
     * @Route("/new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        //check if form was submitted
        if ($request->isMethod('POST')){

            $nationalid = $this->get('request')->request->get('wit_modelbundle_user')['nationalid'];
            $firstname = $this->get('request')->request->get('wit_modelbundle_user')['firstname'];
            $lastname = $this->get('request')->request->get('wit_modelbundle_user')['lastname'];
            //$password = $this->get('request')->request->get('wit_modelbundle_user')['password'];

            $UserEntity = new User();

            //set extra fields
            $UserEntity->setUsername($nationalid);
            $UserEntity->setIsActive(1);
            $UserEntity->setDateCreated(new \DateTime()); // updating date created..
            $UserEntity->setFullnameEnglish($firstname." ".$lastname);
            
            //$encoder = $this->container->get('security.encoder_factory')->getEncoder($UserEntity);
            //$UserEntity->setPassword($encoder->encodePassword($password, $UserEntity->getSalt()));

            //temporary password is: "password"
            $UserEntity->setSalt("7149bd71ce64d92ebfe887e557c60e87");//temporary
            $UserEntity->setPassword("22eb2846c01f2be2cdbb18890291e2de5124c2b5"); //temporary

            //form to create a User entity.
            $form = $this->createForm(new UserType(), $UserEntity, array(
                'action' => $this->generateUrl('wit_admin_user_new'),
                'method' => 'POST',
                'attr' => array(
                    'id'    => 'add-new-user-form',
                )
            ));

            //dump($form);exit;

            $form->handleRequest($request);

            if ($form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($UserEntity);
                $em->flush();

                /*
                 * User was created..
                 * Redirect user
                 */
                $this->get('session')->getFlashBag()->set('error', 'User was added successfully..');
                return $this->redirect($this->generateUrl('wit_admin_user_index'));
            }else{
                //dump($form->getErrors());
                echo "Invalid form.. Please click browser back button to go back..";
                exit;
            }

        }
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/update/{id}")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $userEntity = $em->getRepository('ModelBundle:User')->find($id);

        if (!$userEntity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $userEntity, array(
            'action' => $this->generateUrl('wit_admin_user_update', array('id' => $userEntity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'id' => 'update-user-form',
                'novalidate' => 'novalidate',
            )
        ))->remove('password'); //this will remove password and roles fields from form before submitting

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Updated Successfully..');
            return $this->redirect($this->generateUrl('wit_admin_user_index'));
        }else{

            echo "form not valid";

            echo "<pre>";
            var_dump($request->request->all());
            echo "</pre>";

            //$editForm->submit($request);
            echo $editForm->getErrorsAsString();

            exit;
        }
    }

    /**
     * Activate or block user
     *
     * Parameters:
     * code (1=activate, 2=block)
     * id (user id)
     *
     * @Route("/{id}/status/{code}")
     * @Template()
     */
    public function statusAction(Request $request, $id, $code)
    {
        if (!empty($id) AND !empty($code)){

            $em = $this->getDoctrine()->getManager();

            $userEntity = $em->getRepository('ModelBundle:User')->find($id);

            if (!$userEntity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            if($code==1){ //activate user
                $userEntity->setIsActive(1);
            }else if($code==2){ //block user
                $userEntity->setIsActive(0);
            }

            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'User "'.$userEntity->getFirstname().' '.$userEntity->getLastname().'" was updated successfully..');
            return $this->redirect($this->generateUrl('wit_admin_user_index'));
        }
    }

    /**
     * Reset password to default "password"
     *
     * Parameters:
     * id (user id)
     *
     * @Route("/{id}/reset-password-to-default")
     * @Template()
     */
    public function resetPasswordToDefaultAction(Request $request, $id)
    {
        if (!empty($id)){

            $em = $this->getDoctrine()->getManager();

            $userEntity = $em->getRepository('ModelBundle:User')->find($id);

            if (!$userEntity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            //default password will be "password"
            $userEntity->setSalt("7149bd71ce64d92ebfe887e557c60e87");//temporary
            $userEntity->setPassword("22eb2846c01f2be2cdbb18890291e2de5124c2b5"); //temporary

            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Password was (Reset to Default) for user "'.$userEntity->getFirstname().' '.$userEntity->getLastname());
            return $this->redirect($this->generateUrl('wit_admin_user_index'));
        }
    }

    /**
     * send login info to user by email
     *
     * Parameters:
     * id (user id)
     *
     * @Route("/{id}/send-login-info-to-user")
     * @Template()
     */
    public function sendLoginInfoToUserAction(Request $request, $id)
    {
        if (!empty($id)){

            $em = $this->getDoctrine()->getManager();

            $userEntity = $em->getRepository('ModelBundle:User')->find($id);

            if (!$userEntity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $email = $userEntity->getEmail();
            $fullname = $userEntity->getFirstname().' '.$userEntity->getLastname();

            //validate email address
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                
                $subject = "Forwarded - Your account information at (WIT)";

                $messageBody = "<p><strong>Dear ".$fullname.',</strong></p>';
                $messageBody .= "<br />Your account have been created at http://wit.edu.sa, please use following details to login to your account:";
                $messageBody .= "<p><strong>USERNAME:</strong> ".$userEntity->getUsername()."<br />";
                $messageBody .= "<strong>PASSWORD:</strong> your password</p><br />";
                $messageBody .= "<strong>Best Regards,</strong><br />";
                $messageBody .= "Al Watania Poultry Institute Of Technology (WIT)";

                $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom('noreply@wit.edu.sa')
                    ->setTo(array($email=>$fullname))
                    ->setBody($messageBody,'text/html');

                $this->get('mailer')->send($message);

            } else {
                //not valid email address
            }

            $this->get('session')->getFlashBag()->set('error', 'Account information was sent to user "'.$userEntity->getFirstname().' '.$userEntity->getLastname());
            return $this->redirect($this->generateUrl('wit_admin_user_index'));
        }
    }

    /**
     * Creates a form for search users
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm()
    {
        $form = $this->createForm(new SearchUserType(), new User(), array(
            'action' => $this->generateUrl('wit_admin_user_search'),
            'method' => 'POST',
            'attr'   => array(
                'novalidate' => 'novalidate',
                'id' => 'search_application_form',
            ),
        ));

        return $form;
    }


    /**
     * @Route("/search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        if ($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();

            /*
             * Getting submitted fields through filter/search form
             * And getting instance of respective entity for each submitted field as required
             */
            $firstname  = $this->get('request')->request->get('wit_modelbundle_user')['firstname'];
            $lastname   = $this->get('request')->request->get('wit_modelbundle_user')['lastname'];
            $email      = $this->get('request')->request->get('wit_modelbundle_user')['email'];
            $nationalid = $this->get('request')->request->get('wit_modelbundle_user')['nationalid'];
            $mobile     = $this->get('request')->request->get('wit_modelbundle_user')['mobile'];
            $gender     = $em->getRepository("ModelBundle:Gender")->find($this->get('request')->request->get('wit_modelbundle_user')['gender']);
            $country    = $em->getRepository("ModelBundle:Country")->find($this->get('request')->request->get('wit_modelbundle_user')['country']);

            /*
             * Getting repository for User entity
             * And creating criteria for it
             */
            $UsersRepository = $em->getRepository('ModelBundle:User');

            $criteria = new Criteria();

            if($firstname){
                $criteria->andWhere($criteria->expr()->eq('firstname', $firstname));
            }
            if($lastname){
                $criteria->andWhere($criteria->expr()->eq('lastname', $lastname));
            }
            if($email){
                $criteria->andWhere($criteria->expr()->eq('email', $email));
            }
            if($nationalid){
                $criteria->andWhere($criteria->expr()->eq('nationalid', $nationalid));
            }
            if($mobile){
                $criteria->andWhere($criteria->expr()->eq('mobile', $mobile));
            }
            if($gender){
                $criteria->andWhere($criteria->expr()->eq('gender', $gender));
            }
            if($country){
                $criteria->andWhere($criteria->expr()->eq('country', $country));
            }

            $criteria->orderBy(array('id' => 'ASC'));

            $result = $UsersRepository->matching($criteria);

            $searchForm = $this->createSearchForm();
            
            /*
             * Populating search/filter form on this page 
             */
            $searchForm->get('firstname')->setData($firstname);
            $searchForm->get('lastname')->setData($lastname);
            $searchForm->get('email')->setData($email);
            $searchForm->get('nationalid')->setData($nationalid);
            $searchForm->get('mobile')->setData($mobile);
            $searchForm->get('mobile')->setData($mobile);
            $searchForm->get('gender')->setData($gender);
            $searchForm->get('country')->setData($country);

            return array(
                'searchResult' => $result,
                'searchform'   => $searchForm->createView(),
            );
        }else{

            /*
             * Redirect user if came without search/filter form submission
             */
            $this->get('session')->getFlashBag()->set('error', 'No result was returned, Please use "Search/Filter User Form" ');
            return $this->redirect($this->generateUrl('wit_admin_user_index'));

        }//end: isPost check
    }

    /**
     * Deletes a entity.
     *
     * @Route("/delete/{id}")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        if($id){

            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('ModelBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->set('error', 'Successfully deleted user "'.$entity->getFirstname().' '.$entity->getLastname().'"');
            return $this->redirect($this->generateUrl('wit_admin_user_index'));
        }
    }
    
    /**
     * This will bulk upload users
     *
     * @Route("/bulk-upload")
     * @Template()
     */
    public function bulkUploadAction(Request $request)
    {
        if ($request->isMethod('POST')){
            
            // Set path to CSV file
            $uploadedCSVFile  = $_FILES["upload1"]["tmp_name"];
            
            //this variable has data array from CSV file
            $CSVArray = $this->readCSV($uploadedCSVFile);
            
            //removing header columns from array
            $removedHeader = array_shift($CSVArray);
            
            //echo '<pre>';
            //print_r($CSVArray);
            //echo '</pre>';
            
            $em = $this->getDoctrine()->getManager();
            
            $role_user = $em->getRepository('ModelBundle:Role')->find(4); //instance of ROLE_USER
            
            $totalImportedUsers = 0;
            $importedUsers = array();

            foreach($CSVArray as $element){
                
                $firstname  = $element[0];
                $lastname   = $element[1];
                $email      = $element[2];
                $nationalid = $element[3];
                //$fullname_english   = $element[4];
                $fullname_english   = $element[0]." ".$element[1];
                //$fullname_arabic    = $element[5];
                $traineeid          = $element[4]; //wit id / could be left 00-00-00
                $dateJoined         = $element[5];

                //check required fields if empty
                if ( !empty($firstname) OR !empty($lastname) OR !empty($email) OR !empty($nationalid) OR !empty($traineeid) OR !empty($dateJoined) ) {
                    
                    //nothing is empty as of requirements user can be stored in db
                    $UserEntity = new User();
                
                    $UserEntity->setFirstname($firstname);
                    $UserEntity->setLastname($lastname);
                    $UserEntity->setEmail($email);
                    $UserEntity->setNationalid($nationalid);
                    
                    $UserEntity->setUsername($nationalid);
                    $UserEntity->setIsActive(1);
                    $UserEntity->setDateUpdated(new \DateTime());
                    $UserEntity->setDateCreated(new \DateTime());

                    $UserEntity->setFullnameEnglish($fullname_english);
                    //$UserEntity->setFullnameArabic($fullname_arabic);
                    $UserEntity->setTraineeid($traineeid);

                    $datetimeObj = new \DateTime();
                    $UserEntity->setDateJoined($datetimeObj->createFromFormat('d/m/Y', $dateJoined));       
                    
                    //default password will be "password"
                    $UserEntity->setSalt("7149bd71ce64d92ebfe887e557c60e87");//temporary
                    $UserEntity->setPassword("22eb2846c01f2be2cdbb18890291e2de5124c2b5"); //temporary
                    
                    //TODO: user role needs to be setup as ROLE_USER
                    $UserEntity->setRoles($role_user);
                    
                    $em->persist($UserEntity);
                    $em->flush();

                    if ($UserEntity->getId()){ //check if user was saved
                        $totalImportedUsers++;
                        $importedUsers[] = $fullname_english.' ('.$traineeid.')';
                    }

                }//end if required fields checker
                
            }//end foreach
            
            //sending individual imported user info
            $this->get('session')->getFlashBag()->set('info', implode(', ', $importedUsers));
            //send success message
            $this->get('session')->getFlashBag()->set('error', '('.$totalImportedUsers.') Users were successfully uploaded..');
            //if any user missed while uploading..
            $this->get('session')->getFlashBag()->set('notice', '');
            return $this->redirect($this->generateUrl('wit_admin_user_bulkupload'));
        }
        return array();
    }
    
    /**
     * User Roles
     *
     * @Route("/roles")
     * @Template()
     */
    public function rolesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('ModelBundle:Role')->findBy(array(), array('id' => 'DESC'));

        //form to create a Role
        $addUserRoleform = $this->createForm(new RoleType(), new Role(), array(
            'action' => $this->generateUrl('wit_admin_user_newrole'),
            'method' => 'POST',
            'attr' => array(
                'id'    => 'add-new-role-form',
            )
        ));

        return array(
            'roles' => $roles,
            'addUserRoleForm' => $addUserRoleform->createView(),
        );
    }
    
    /**
     * Create a new role entity.
     *
     * @Route("/role/new")
     * @Template()
     */
    public function newRoleAction(Request $request)
    {
        $roleEntity = new Role();

        //form to create a Role entity.
        $form = $this->createForm(new RoleType(), $roleEntity, array(
            'action' => $this->generateUrl('wit_admin_user_newrole'),
            'method' => 'POST',
            'attr' => array(
                'id' => 'add-new-role-form',
            )
        ));

        //check if form was submitted
        if ($request->isMethod('POST')){

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($roleEntity);
                $em->flush();

                /*
                 * Role was created..
                 * Redirect user to add role page
                 */
                $this->get('session')->getFlashBag()->set('error', 'Role was added successfully..');
                return $this->redirect($this->generateUrl('wit_admin_user_roles'));
            }

        }

        return array(
            'roleEntity' => $roleEntity,
            'form'   => $form->createView(),
        );
    }
    
    //this function reads CSV file and returns an array    
    private function readCSV($csvFile){
        
        $file_handle = fopen($csvFile, 'r');
        
        while (!feof($file_handle) ) {
            $line_of_text[] = fgetcsv($file_handle, 1024);
        }
        
        fclose($file_handle);
        
        return $line_of_text;
    }
}