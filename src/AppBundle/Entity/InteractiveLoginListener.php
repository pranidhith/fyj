<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\JobSeeker;
use AppBundle\Entity\JobRecruiter;

class LoginListener {

    public function onInteractiveLogin(InteractiveLoginEvent $event){
        $user = $event->getAuthenticationToken()->getUser();

        if ($user) {
            $user->setAccLastAccessedDate(new \DateTime('now'));
            $this->getDoctrine()->getEntityManager()->persist($user);
            $user->flush();
        }
    }
}