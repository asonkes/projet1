<?php

namespace App\Entity;

use App\Entity\ProjectTasks;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, ProjectTasks>
     */
    #[ORM\OneToMany(targetEntity: ProjectTasks::class, mappedBy: 'project')]
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
            $projectTask->setProject($this);
        }

        return $this;
    }

    public function removeProjectTask(ProjectTasks $projectTask): static
    {
        if ($this->projectTasks->removeElement($projectTask)) {
            // set the owning side to null (unless already changed)
            if ($projectTask->getProject() === $this) {
                $projectTask->setProject(null);
            }
        }

        return $this;
    }
}
