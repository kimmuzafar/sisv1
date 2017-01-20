<?php

namespace Wit\UserBundle\Helper;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Wit\ModelBundle\Entity\Application;
use Wit\ModelBundle\Entity\UserLogs;

/*
 * PURPOSE:
 * This is registered as service, and it's created to keep common / non-database user functions in it
 * 
 * CALLING PROCEDURE:
 * Functions in this class can be called 
 * echo $this->get('wit.helper.user')->getOnlineUserFullname();
 */
class UserHelper
{
    /**
     * Router
     *
     * @var Router
     */
    protected $router;

    private $securityContext;

    /**
     * @param Router $router The router
     */
    public function __construct($container, Router $router)
    {
        $this->container    = $container;
        $this->router = $router;

        $this->securityContext = $this->container->get('security.context');
    }

    public function getOnlineUser(){
        return $this->securityContext->getToken()->getUser();
    }

    public function getOnlineUserId(){
        return $this->securityContext->getToken()->getUser()->getId();
    }

    public function getOnlineUserFullname(){
        return $this->securityContext->getToken()->getUser()->getFirstname()." ".$this->securityContext->getToken()->getUser()->getLastname();
    }

    public function getOnlineUserTotalApplications(){
        return count($this->securityContext->getToken()->getUser()->getApplications());
    }
}