<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
// GetResponseForControllerResultEvent was changed to ViewEvent
use Symfony\Component\HttpKernel\Event\ViewEvent;

class PasswordHashSubscriber implements EventSubscriberInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher=$passwordHasher;
    }

    public static function getSubscribedEvents(){
        return [
            //hashPassword is the name of the function that will be called?
            KernelEvents::VIEW=>['hashPassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function hashPassword(ViewEvent $event)
    {
        $user=$event->getControllerResult();
        //$event has all the info about the request
        $method=$event->getRequest()->getMethod();

        //Request::METHOD_POST is just a constant...
        if (!$user instanceof User || Request::METHOD_POST !== $method){
            return;
        }

        //API platform is the one writing the data
        //This event happens after data is validated but before it is entered into db
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $user->getPassword())
        );
    }


}