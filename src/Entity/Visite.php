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

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avis = null;

    #[ORM\Column(nullable: true)]
    private ?int $tempmin = null;

    #[ORM\Column(nullable: true)]
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

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function getNote(): ?int
    {
        return $this->note;
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
    public function validate(ExecutionContextInterface $context) {
        $file = $this->getImageFile();
        if($file != null && $file != "") {
            $poids = @filesize($file);
            if($poids != false && $poids > 512000){
                $context->buildViolation("Cette image est trop lourde (500Ko Max)")
                        ->atPath('imageFile')
                        ->addViolation();
            }
            $infosImage = @getimagesize($file);
            if(infosImage == false){
                $context->buildViolation("Ce fichier n'est pas une image")
                        ->atPath('imageFile')
                        ->addViolation();
            }
        }
        
    }
}
