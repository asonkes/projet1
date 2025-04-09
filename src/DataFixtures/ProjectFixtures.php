<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= 10; $i++) {

            $project = new Project();
            $project->setName($faker->text(10));

            // On doit récupérer un "user" aléatoire entre 1 et 10
            $user = $this->getReference('user_' . rand(1, 10), User::class);

            $project->setUser($user);

            $manager->persist($project);
        }

        $manager->flush();
    }

    // Indique que cette fixture dépend de UserFixtures
    // Et on fait en sorte que celle-ci soit chargée après "User"
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
