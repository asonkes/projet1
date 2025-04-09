<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\Project;
use App\Entity\ProjectTasks;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProjectTasksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $projectTasks = new ProjectTasks();

            // On récupère l'id de chaque "projet"
            $project = $this->getReference('proj_' . $i, Project::class);
            $projectTasks->setProject($project);

            // On récupère l'id de chaque "tâche"
            $task = $this->getReference('task_' . $i, Task::class);
            $projectTasks->setTask($task);

            $manager->persist($projectTasks);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProjectFixtures::class,
            TaskFixtures::class,
        ];
    }
}
