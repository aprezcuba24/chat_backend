<?php

namespace App\Entity;

use App\Entity\Bot\Bot;
use App\Entity\Chat\Channel;
use App\Entity\Chat\Message;
use App\Entity\Chat\Workspace;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\WorkspaceMember\Create;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"read"}},
 *  attributes={
 *      "security"="is_granted('WORKSPACE_ACTIVE')",
 *  },
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read", "message"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     * @Groups({"read", "message"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "message"})
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity=Workspace::class, mappedBy="members")
     */
    private $workspaces;

    /**
     * @ORM\ManyToMany(targetEntity=Channel::class, mappedBy="members")
     */
    private $channels;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="owner")
     */
    private $messages;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Workspace::class, mappedBy="owner", orphanRemoval=true)
     */
    private $ownWorkspaces;

    /**
     * @ORM\OneToOne(targetEntity=Bot::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $bot;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apiToken;

    public function __construct()
    {
        $this->workspaces = new ArrayCollection();
        $this->channels = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->ownWorkspaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        if ($this->getApiToken()) {
            $roles[] = 'ROLE_BOT';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|Workspace[]
     */
    public function getWorkspaces(): Collection
    {
        return $this->workspaces;
    }

    public function addWorkspace(Workspace $workspace): self
    {
        if (!$this->workspaces->contains($workspace)) {
            $this->workspaces[] = $workspace;
            $workspace->addMember($this);
        }

        return $this;
    }

    public function removeWorkspace(Workspace $workspace): self
    {
        if ($this->workspaces->removeElement($workspace)) {
            $workspace->removeMember($this);
        }

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
            $channel->addMember($this);
        }

        return $this;
    }

    public function removeChannel(Channel $channel): self
    {
        if ($this->channels->removeElement($channel)) {
            $channel->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setOwner($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getOwner() === $this) {
                $message->setOwner(null);
            }
        }

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|Workspace[]
     */
    public function getOwnWorkspaces(): Collection
    {
        return $this->ownWorkspaces;
    }

    public function addOwnWorkspace(Workspace $ownWorkspace): self
    {
        if (!$this->ownWorkspaces->contains($ownWorkspace)) {
            $this->ownWorkspaces[] = $ownWorkspace;
            $ownWorkspace->setOwner($this);
        }

        return $this;
    }

    public function removeOwnWorkspace(Workspace $ownWorkspace): self
    {
        if ($this->ownWorkspaces->removeElement($ownWorkspace)) {
            // set the owning side to null (unless already changed)
            if ($ownWorkspace->getOwner() === $this) {
                $ownWorkspace->setOwner(null);
            }
        }

        return $this;
    }

    public function getBot(): ?Bot
    {
        return $this->bot;
    }

    public function setBot(?Bot $bot): self
    {
        $this->bot = $bot;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $bot ? null : $this;
        if ($bot->getUser() !== $newUser) {
            $bot->setUser($newUser);
        }

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
