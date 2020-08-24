<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "GET"={"path"="/notes"},
 *          "POST"={"path"="/notes"}
 *     },
 *     itemOperations={
 *          "GET"={"path"="/notes/{id}"},
 *          "DELETE"={"path"="/notes/{id}"},
 *          "PATCH"={"path"="/notes/{id}"}
 *     },
 *     normalizationContext={"groups"={"notes_read"}},
 *     subresourceOperations={"api_project_note_get_subresource"={"normalization_context"={"groups"="notes_subresource"}}}
 * )
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"notes_read", "project_read", "notes_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"notes_read", "project_read", "notes_subresource"})
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"notes_read", "project_read", "notes_subresource"})
     */
    private $description;

    /**
     * @Groups({"notes_read"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Groups({"notes_read"})
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="notes")
     */
    private $project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
