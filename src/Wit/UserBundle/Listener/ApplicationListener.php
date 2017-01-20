<?php

namespace Wit\UserBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Wit\ModelBundle\Entity\Application;
use Wit\ModelBundle\Entity\UserLogs;

class ApplicationListener
{
    /**
     * Router
     *
     * @var Router
     */
    protected $router;

    /**
     * @param Router $router The router
     */
    public function __construct($container, Router $router)
    {
        $this->container    = $container;
        $this->router = $router;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $securityContext = $this->container->get('security.context');

        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // perhaps you only want to act on some "Application" entity
        if ($entity instanceof Application) {
            // ... do something with the Application

            $onlineUserId = $securityContext->getToken()->getUser()->getId();
            $onlineUserFullName = $securityContext->getToken()->getUser()->getFirstname()." ".$securityContext->getToken()->getUser()->getLastname();

            /*
             * Application was submitted..
             * Create log for the same
             */
            $userLog = new UserLogs();
            $userLog->setDetail("Submitted an application successfully! <a href='".$this->router->generate('wit_sad_application_view', array('id'=>$entity->getId()))."'>Read More..</a>");
            $userLog->setGenerator($onlineUserFullName);
            $userLog->setDateCreated(new \DateTime());
            $userLog->setIsActive(1);
            $userLog->setFromUserId($onlineUserId); //this will help in figuring out who created this log

            $userLog->setIsForTod(1); //if 1 it will display in tod notifications
            $userLog->setIsForSad(1); //if 1 it will display in sad notifications
            $userLog->setIsForUser(0); //if 1 it will display for all users registered.
            //$userLog->setUserRoleUserId($onlineUserId);

            $entityManager->persist($userLog);
            $entityManager->flush();

        }
    }
}