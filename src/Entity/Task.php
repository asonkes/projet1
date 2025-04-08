<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $name = null;

    /**
     * @var Collection<int, ProjectTasks>
     */
    #[ORM\OneToMany(targetEntity: ProjectTasks::class, mappedBy: 'task')]
    private Collection $projectTasks;

    public function __construct()
    {
        $this->projectTasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ProjectTasks>
     */
    public function getProjectTasks(): Collection
    {
        return $this->projectTasks;
    }

    public function addProjectTask(ProjectTasks $projectTask): static
    {
        if (!$this->projectTasks->contains($projectTask)) {
            $this->projectTasks->add($projectTask);
            $projectTask->setTask($this);
        }

        return $this;
    }

    public function removeProjectTask(ProjectTasks $projectTask): static
    {
        if ($this->projectTasks->removeElement($projectTask)) {
            // set the owning side to null (unless already changed)
            if ($projectTask->getTask() === $this) {
                $projectTask->setTask(null);
            }
        }

        return $this;
    }
}
