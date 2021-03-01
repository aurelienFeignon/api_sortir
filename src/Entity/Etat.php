<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
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
     * @Assert\Choice(
     *     choices= {"Créée", "Cloturée"},
     *     message="Le libéllé doit etre Créée ou Cloturée"
     * )
     * @Assert\Length(
     *     min=5, max=8,
     *     minMessage="Le libellé doit faire plus de 5 caracteres",
     *     maxMessage="Le nom doit faire moins de 8 caracteres"
     * )
     * @ORM\Column(type="string", length=8)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=sortie::class, mappedBy="etat")
     */
    private $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }



    /**
     * @return Collection|sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorties(sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setEtat($this);
        }

        return $this;
    }

    public function removeSorties(sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getEtat() === $this) {
                $sorty->setEtat(null);
            }
        }

        return $this;
    }
}
