<?php

namespace App\Service;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\EventDispatcher\Event;

readonly class UserService
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $hasher, private EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * @param User $user
     * @param string $plainPassword
     * @return void
     */
    public function registerUser(User $user, string $plainPassword) : void
    {
        $user
            ->setPassword(
                $this->hasher->hashPassword(
                    $user,
                    $plainPassword
                )
            )
            ->setCreatedAt(new DateTimeImmutable())
        ;
        $this->saveUser($user, new UserCreatedEvent($user));
    }

    public function saveUser(User $user, ?Event $event = null): void
    {
        if ($event) {
            $this->dispatcher->dispatch($event);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}