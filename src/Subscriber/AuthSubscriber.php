<?php

namespace App\Subscriber;

use App\Event\UserCreatedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly class AuthSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailerInterface $mailer)
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
        $email = new TemplatedEmail();
        $email
            ->from('no-reply@nytodev.fr')
            ->to($user->getEmail())
            ->subject('Nytodev | Activation du compte')
            ->htmlTemplate('emails/activate_account.html.twig')
            ->context([
                'user' => $user
            ]);
        $this->mailer->send($email);
    }
}
