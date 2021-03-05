<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @Groups({"sortie:read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min=3, max=100,
     *     minMessage="Le nom doit faire plus de 3 caracteres",
     *     maxMessage="Le nom doit faire moins de 100 caracteres"
     * )
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @Groups({"sortie:read"})
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @Groups({"sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="integer")
     * @Assert\Positive(message="La durée doit etre positive")
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @Groups({"sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="integer")
     * @Assert\Positive(message="Le nombre d'inscription  doit etre positive")
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionMax;

    /**
     * @Groups({"sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min=3, max=255,
     *     minMessage="L'info sur la sortie doit faire plus de 3 caracteres",
     *     maxMessage="L'info sur la sortie doit faire moins de 255 caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $infosSortie;

    /**
     * @Groups({"sortie:read"})
     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="sorties")
     */
    private $participants;

    /**
     * @Groups({"sortie:read"})
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="sortiesOrganisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @Groups({"sortie:read"})
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @Groups({"sortie:read"})
     * @ORM\Column(type="date")
     */
    private $dateLimiteInscriptions;

    /**
     * @Groups({"sortie:read"})
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @Groups({"sortie:read"})
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sortie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateLimiteInscriptions(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscriptions;
    }

    public function setDateLimiteInscriptions(\DateTimeInterface $dateLimiteInscritions): self
    {
        $this->dateLimiteInscriptions = $dateLimiteInscritions;

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

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }
}
