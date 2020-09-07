<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(min=3, max=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="modules")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Subject", mappedBy="module")
     */
    private $subjects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsersHaveModules", mappedBy="module")
     */
    private $usersHaveModules;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->usersHaveModules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSubjects(): ?Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;

            $subject->setModule($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);

            if ($subject->getModule() === $this) {
                $subject->setModule(null);
            }
        }

        return $this;
    }

    public function getUsersHaveModules(): ?Collection
    {
        return $this->usersHaveModules;
    }

    public function addUsersHaveModules(UsersHaveModules $usersHaveModules): self
    {
        if (!$this->usersHaveModules->contains($usersHaveModules)) {
            $this->usersHaveModules[] = $usersHaveModules;

            $usersHaveModules->setModule($this);
        }

        return $this;
    }

    public function removeUsersHaveModules(UsersHaveModules $usersHaveModules): self
    {
        if ($this->usersHaveModules->contains($usersHaveModules)) {
            $this->usersHaveModules->removeElement($usersHaveModules);

            if ($usersHaveModules->getModule() === $this) {
                $usersHaveModules->setModule(null);
            }
        }

        return $this;
    }
}
