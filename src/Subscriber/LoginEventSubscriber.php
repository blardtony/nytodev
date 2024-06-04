<?php

namespace App\Subscriber;

use App\Entity\User;
use App\Event\BadPasswordLoginEvent;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

readonly class LoginEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            BadPasswordLoginEvent::class => 'onAuthenticationFailure',
            LoginSuccessEvent::class => 'onAuthenticationSuccess'
        ];
    }

    public function onAuthenticationFailure(): void
    {
    }

    public function onAuthenticationSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof User) {
            $user->setLastLoginAt(new DateTimeImmutable());
            $this->entityManager->flush();
        }
    }
}