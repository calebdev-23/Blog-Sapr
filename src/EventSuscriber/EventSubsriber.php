<?php
namespace App\EventSuscriber;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EventSubsriber implements EventSubscriberInterface{

    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
          KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE],
        ];
    }

    public function hashPassword(ViewEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || !in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])) {
           return;
        }
        $password = $this->hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($password);
    }

}