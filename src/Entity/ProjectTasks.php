<?php

namespace App\Entity;

use App\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectTasksRepository;


#[ORM\Entity(repositoryClass: ProjectTasksRepository::class)]
class ProjectTasks
{
    // J'ai mis des id sur les 2 tables en lien 
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'projectTasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    // J'ai mis des id sur les 2 tables en lien 
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'projectTasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }
}
