<?php

namespace Wit\AuthBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+
// use Symfony\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.0.x

/**
 * Custom login listener.
 */
class LoginListener
{
    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;
    
    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    private $router;

    /**
     * Constructor
     * 
     * @param SecurityContext $securityContext
     * @param Doctrine        $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine)
    {
        $this->securityContext = $securityContext;
        $this->em              = $doctrine->getEntityManager();
    }
    
    /**
     * Do the magic.
     * 
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if ($this->securityContext->isGranted('ROLE_STUDENT')) {
            // user has just logged in

            echo "Hello Student";
            exit;
            
        }
        
        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            // user has logged in using remember_me cookie

            echo "Hello Admin";
            exit;
        }
        
        // do some other magic here
        $user = $event->getAuthenticationToken()->getUser();
        $user->setLastLogin(new \DateTime());
    }
}