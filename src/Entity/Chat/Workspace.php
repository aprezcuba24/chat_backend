<?php

namespace App\Entity\Chat;

use App\Entity\User;
use App\Repository\Chat\WorkspaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=WorkspaceRepository::class)
 * @ApiResource(
 *  attributes={"security"="is_granted('ROLE_USER')"},
 *  normalizationContext={"groups"={"workspace:read"}},
 *  itemOperations={
 *    "get",
 *    "put" = { "security" = "is_granted('WORKSAPCE_EDIT', object)" },
 *    "delete" = { "security" = "is_granted('WORKSAPCE_DELETE', object)" },
 *  },
 * )
 */
class Workspace
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"workspace:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"workspace:read"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="workspaces")
     * @ApiSubresource
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity=Channel::class, mappedBy="workspace")
     * @ApiSubresource
     */
    private $channels;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ownWorkspaces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"workspace:read"})
     */
    private $owner;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->channels = new ArrayCollection();
    }

    public function isOwner(User $user)
    {
        if (!$this->getOwner()) {
            return false;
        }

        return $this->getOwner()->getId() === $user->getId();
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

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $user): self
    {
        if (!$this->members->contains($user)) {
            $this->members[] = $user;
        }

        return $this;
    }

    public function removeMember(User $user): self
    {
        $this->members->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|Channel[]
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(Channel $channel): self
    {
        if (!$this->channels->contains($channel)) {
            $this->channels[] = $channel;
            $channel->setWorkspace($this);
        }

        return $this;
    }

    public function removeChannel(Channel $channel): self
    {
        if ($this->channels->removeElement($channel)) {
            // set the owning side to null (unless already changed)
            if ($channel->getWorkspace() === $this) {
                $channel->setWorkspace(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
