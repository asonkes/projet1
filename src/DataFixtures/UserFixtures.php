<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('audrey.sonkes@gmail.com');
        $admin->setPassword(
            $this->userPasswordHasher->hashPassword($admin, '123456!')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        // Référence de l'admin
        $this->addReference('user_1', $admin);

        $faker = Factory::create('fr_FR');
        for ($i = 2; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, 'secret!')
            );

            $manager->persist($user);

            // Ajout de la référence pour chaque utilisateur
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
