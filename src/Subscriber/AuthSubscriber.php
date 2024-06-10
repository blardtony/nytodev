<?php

namespace App\Subscriber;

use App\Event\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class AuthSubscriber implements EventSubscriberInterface
{
    public function __construct()
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::class => 'onRegister',
        ];
    }

    public function onRegister(UserCreatedEvent $event): void
    {
        $user = $event->getUser();
        //TODO : Send email to user
    }
}