<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $userWithAccountActivate = new User();
        $userWithAccountActivate->setEmail('user@test.fr')
            ->setPassword($this->hasher->hashPassword($userWithAccountActivate, 'password'))
            ->setRoles(['ROLE_USER'])
            ->setToken(null)
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($userWithAccountActivate);

        $userWithAccountNotActivate = new User();
        $userWithAccountNotActivate->setEmail('notUser@test.fr')
            ->setPassword($this->hasher->hashPassword($userWithAccountNotActivate, 'password'))
            ->setRoles(['ROLE_USER'])
            ->setToken('token')
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($userWithAccountNotActivate);
        $manager->flush();
    }
}
