<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @Groups("sortie:read")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"ville:read", "sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min=3, max=150,
     *     minMessage="Le nom doit faire plus de 3 caracteres",
     *     maxMessage="Le nom doit faire moins de 150 caracteres"
     * )
     * @ORM\Column(type="string", length=150)
     */
    private $nom;

    /**
     * @Groups({"ville:read", "sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min=3, max=255,
     *     minMessage="La rue doit faire plus de 3 caracteres",
     *     maxMessage="Le rue doit faire moins de 255 caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $rue;

    /**
     * @Groups({"ville:read", "sortie:read"})
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type(type="float")
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @Groups({"ville:read", "sortie:read"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="lieu")
     */
    private $sortie;

    /**
     * @Groups({"sortie:read"})
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="lieux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;


    /**
     * Lieu constructor.
     * @param $nom
     * @param $rue
     * @param $latitude
     * @param $longitude
     */
    public function __construct($nom, $rue, $latitude, $longitude)
    {
        $this->nom = $nom;
        $this->rue = $rue;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->sortie = new ArrayCollection();
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortie(): Collection
    {
        return $this->sortie;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sortie->contains($sortie)) {
            $this->sortie[] = $sortie;
            $sortie->setLieu($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sortie->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getLieu() === $this) {
                $sortie->setLieu(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }


}
