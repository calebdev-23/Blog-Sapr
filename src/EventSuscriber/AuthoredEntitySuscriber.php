<?php
namespace App\EventSuscriber;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Interface\AuthoredEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class AuthoredEntitySuscriber implements EventSubscriberInterface{

    private $token;
    public function __construct(TokenStorageInterface $tokenStorage){
        $this->token = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['getUserAuthenticated', EventPriorities::PRE_WRITE],
        ];
    }
    public function getUserAuthenticated(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$entity instanceof AuthoredEntityInterface || Request::METHOD_POST !== $method)
        {
            return;
        }
        $author = $this->token->getToken()->getUser();
        $entity->setAuthor($author);
    }

}