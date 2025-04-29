<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
#[Vich\Uploadable] 
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    #[ORM\Column(length: 50)]
    private ?string $pays = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datecreation = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Range(min: 0, max:20)]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avis = null;

    #[ORM\Column(nullable: true)]
    private ?int $tempmin = null;

    #[ORM\Column(nullable: true)]
    #[ Assert\GreaterThan(propertyPath:"tempmin")]
    private ?int $tempmax = null;

    #[ORM\ManyToMany(targetEntity: Environnement::class)]
    private Collection $environnements;

    #[Vich\UploadableField(mapping: "visite", fileNameProperty: "imageName", size: "imageSize")]
    #[Assert\Image(mimeTypes: ["image/jpeg", "image/png"])] // 🔹 Restriction aux formats JPEG et PNG
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->environnements = new ArrayCollection();
    }

    // ✅ Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }
    
    public function setVille(string $ville): self
{
    $this->ville = $ville;
    return $this;
}

    public function getPays(): ?string
    {
        return $this->pays;
    }
    
    public function setPays(string $pays): self
{
    $this->pays = $pays;
    return $this;
}
public function setId(?int $id) {
    $this->id = $id;
    return $this;
}

public function setAvis(?string $avis) {
    $this->avis = $avis;
    return $this;
}

public function setTempmin(?int $tempmin) {
    $this->tempmin = $tempmin;
    return $this;
}

public function setTempmax(?int $tempmax) {
    $this->tempmax = $tempmax;
    return $this;
}

public function setEnvironnements(Collection $environnements) {
    $this->environnements = $environnements;
    return $this;
}

public function setUpdatedAt(?\DateTimeImmutable $updatedAt) {
    $this->updatedAt = $updatedAt;
    return $this;
}

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }
    
    public function setDateCreation(\DateTimeInterface $date): self
{
    $this->datecreation = $date;
    return $this;
}

    public function getNote(): ?int
    {
        return $this->note;
    }
    
    public function setNote(?int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function getTempMin(): ?int
    {
        return $this->tempmin;
    }

    public function getTempMax(): ?int
    {
        return $this->tempmax;
    }

    public function getEnvironnements(): Collection
    {
        return $this->environnements;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // ✅ Getters et setters pour l'image
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable(); // Permet de forcer la mise à jour de l'entité
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
    
    public function getDateCreationString(): ?string
    {
        return $this->datecreation ? $this->datecreation->format('d/m/Y') : null;
    }
    
    #[Assert\Callback]
public function validate(ExecutionContextInterface $context): void
{
    $file = $this->getImageFile();

    if ($file !== null && $file !== '') {
        $poids = @filesize($file);
        if ($poids !== false && $poids > 512000) {
            $context->buildViolation("Cette image est trop lourde (500Ko Max)")
                    ->atPath('imageFile')
                    ->addViolation();
        }

        $infosImage = @getimagesize($file);
        if (!$infosImage) {
            $context->buildViolation("Ce fichier n'est pas une image")
                    ->atPath('imageFile')
                    ->addViolation();
        }
    }
}

}
