<?php

namespace Wit\UserBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Wit\ModelBundle\Entity\User;
use Wit\ModelBundle\Entity\UserLogs;

class RegisterListener
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
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // perhaps you only want to act on some "User" entity
        if ($entity instanceof User) {
            /*
             * Account was created..
             * Create log for the same
             */
            $userLog = new UserLogs();
            $userLog->setDetail("a new account ".$entity->getUsername()." was created and activated successfully <a href='".$this->router->generate('wit_admin_user_index_1', array('id'=>$entity->getId()))."'>Read More..</a>");
            $userLog->setGenerator("System");
            $userLog->setDateCreated(new \DateTime());
            $userLog->setIsActive(1);
            $userLog->setIsForTod(0); //if 1 it will display in tod notifications
            $userLog->setIsForSad(0); //if 1 it will display in sad notifications
            $userLog->setIsForUser(0); //if 1 it will display for all users registered.
            $entityManager->persist($userLog);
            $entityManager->flush();
        }
    }
}