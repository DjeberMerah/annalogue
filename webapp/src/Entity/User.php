<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("mail")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(max=180)
     * @Assert\Email
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank
     * @Assert\Length(max=40)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(min=4)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Module", mappedBy="owner")
     */
    private $modules;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Subject", mappedBy="owner")
     */
    private $subjects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Correction", mappedBy="owner")
     */
    private $corrections;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="owner")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsersHaveModules", mappedBy="user")
     */
    private $usersHaveModules;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->corrections = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->usersHaveModules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->name;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getModules(): ?Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;

            $module->setOwner($this);
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        if ($this->modules->contains($module)) {
            $this->modules->removeElement($module);

            if ($module->getOwner() === $this) {
                $module->setOwner(null);
            }
        }

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

            $subject->setOwner($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);

            if ($subject->getOwner() === $this) {
                $subject->setOwner(null);
            }
        }

        return $this;
    }

    public function getCorrection(): ?Collection
    {
        return $this->corrections;
    }

    public function addCorrection(Correction $correction): self
    {
        if (!$this->corrections->contains($correction)) {
            $this->corrections[] = $correction;

            $correction->setOwner($this);
        }

        return $this;
    }

    public function removeCorrection(Correction $correction): self
    {
        if ($this->corrections->contains($correction)) {
            $this->corrections->removeElement($correction);

            if ($correction->getOwner() === $this) {
                $correction->setOwner(null);
            }
        }

        return $this;
    }

    public function getComments(): ?Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;

            $comment->setOwner($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);

            if ($comment->getOwner() === $this) {
                $comment->setOwner(null);
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

            $usersHaveModules->setUser($this);
        }

        return $this;
    }

    public function removeUsersHaveModules(UsersHaveModules $usersHaveModules): self
    {
        if ($this->usersHaveModules->contains($usersHaveModules)) {
            $this->usersHaveModules->removeElement($usersHaveModules);

            if ($usersHaveModules->getUser() === $this) {
                $usersHaveModules->setUser(null);
            }
        }

        return $this;
    }
}
