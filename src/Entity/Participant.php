<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity("email")
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
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
     * @Assert\Email(message="ceci n'est pas un email")
     * @Assert\Type(type="string")
     * @Assert\Unique(message="L'email doit etre unique")
     * @Assert\Length(
     *     min=3, max=150,
     *     minMessage="L'email doit faire plus de 3 caracteres",
     *     maxMessage="L'email doit faire moins de 150 caracteres"
     * )
     * @ORM\Column(type="string", length=150)
     */
    private $mail;

    /**
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
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sorties;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
     */
    private $sortiesOrganisees;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortiesOrganisees = new ArrayCollection();
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

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
}
