<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *      "GET"={"path"="/projects","openapi_context"={"summary"="Lister les projets","description"="Liste vos propre projet"}},
 *      "POST"={"path"="/projects","openapi_context"={"summary"="Création d'un nouveau projet"}}
 *     },
 *     itemOperations={"GET"={"path"="/projects/{id}"}, "DELETE"={"path"="/projects/{id}"}, "PATCH"={"path"="/projects/{id}"}},
 *     normalizationContext={"groups"={"project_read"}},
 *     subresourceOperations={"notes_get_subresource"={"path"="/projects/{id}/notes"}}
 * )
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"notes_read", "project_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"notes_read", "project_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"notes_read", "project_read"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"project_read"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="project")
     * @ApiSubresource()
     * @Groups({"project_read"})
     */
    private $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setProject($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getProject() === $this) {
                $note->setProject(null);
            }
        }

        return $this;
    }
}
