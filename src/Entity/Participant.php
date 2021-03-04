<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(
 *     fields={"email", "username", "apiToken"},
 *     message="l'email existe deja",
 *     errorPath="username",
 *     message="Le pseudo existe deja")
 */
class Participant implements UserInterface
{
    /**
     * @Groups({"participant:read", "participantUser:read", "sortie:read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"participant:read", "participantUser:read", "sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min=3, max=70,
     *     minMessage="Le nom doit faire plus de 3 caracteres",
     *     maxMessage="Le nom doit faire moins de 70 caracteres"
     * )
     * @ORM\Column(type="string", length=70)
     */
    private $nom;

    /**
     * @Groups({"participant:read", "participantUser:read", "sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min=3, max=70,
     *     minMessage="Le prenom doit faire plus de 3 caracteres",
     *     maxMessage="Le prenomnom doit faire moins de 70 caracteres"
     * )
     * @ORM\Column(type="string", length=70)
     */
    private $prenom;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Length(max="50",
     *     maxMessage="Le pseudo ne peut pas faire plus de 15 caractères")
     * @Groups({"participant:read", "participantUser:read", "sortie:read"})
     * @ORM\Column(type="string", unique=true, length=50)
     */
    private $username;

    /**
     * @Groups({"participant:read", "participantUser:read", "sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Email(message="ceci n'est pas un email")
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min=3, max=150,
     *     minMessage="L'email doit faire plus de 3 caracteres",
     *     maxMessage="L'email doit faire moins de 150 caracteres"
     * )
     * @ORM\Column(type="string", unique=true, length=150)
     */
    private $email;

    /**
     * @Groups("participant:read")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min=3, max=255,
     *     minMessage="Le mot de passe doit faire plus de 3 caracteres",
     *     maxMessage="Le nom doit faire moins de 255 caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Groups({"participant:read", "participantUser:read", "sortie:read"})
     * @Assert\Length(max="15",
     *     maxMessage="Le numero de telephone ne peut pas faire plus de 15 caractères")
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telephone;



    /**
     * @Groups({"participant:read", "participantUser:read", "sortie:read"})
     * @Assert\Length(max="255",
     *     maxMessage="Le chemin de l'image ne peut pas faire plus de 255 caractères")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cheminImg;

    /**
     * @Groups({"participant:read", "participantUser:read"})
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @Groups({"participant:read", "participantUser:read"})
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @Groups({"participant:read", "participantUser:read"})
     * @ORM\Column(type="string", nullable=true)
     */
     private $apiToken;

    /**
     * @Groups("participant:read")
     * @ORM\Column(type="json")
     */
     private $roles=[];

    /**
     * @Groups({"participant:read", "participantUser:read", "sortie:read"})
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(name="campus_id", referencedColumnName="id", nullable=false)
     */
    private $campus;

    /**
     * @Groups({"participant:read", "participantUser:read"})
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sorties;

    /**
     * @Groups({"participant:read", "participantUser:read"})
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
     */
    private $sortiesOrganisees;


    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortiesOrganisees = new ArrayCollection();
        $this->roles[]='ROLE_USER';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
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

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->addParticipant($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSortiesOrganisee(Sortie $sortiesOrganisee): self
    {
        if (!$this->sortiesOrganisees->contains($sortiesOrganisee)) {
            $this->sortiesOrganisees[] = $sortiesOrganisee;
            $sortiesOrganisee->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOrganisee(Sortie $sortiesOrganisee): self
    {
        if ($this->sortiesOrganisees->removeElement($sortiesOrganisee)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOrganisee->getOrganisateur() === $this) {
                $sortiesOrganisee->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @return mixed
     */
    public function getRoles():array
    {
        $roles=$this->roles;
        $roles[]= 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param mixed $roles
     */
    public function addRoles($rolesAdd): void
    {
        $this->roles[]=$rolesAdd;
    }




    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }



    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCheminImg(): ?string
    {
        return $this->cheminImg;
    }

    public function setCheminImg(?string $cheminImg): self
    {
        $this->cheminImg = $cheminImg;

        return $this;
    }
}
