<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{

     public const STATUS_PENDING = "PENDING";
     public const STATUS_PAID = "PAID";


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message : "Veuiilez remplir ce champ",
    )]
    private $FullName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message : "Veuiilez remplir ce champ",
    )]
    private $adress;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message : "Veuiilez remplir ce champ",
    )]
    private $postalCode;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message : "Veuiilez remplir ce champ",
    )]
    private $city;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'purchases')]
    
    private $user;

    #[ORM\Column(type: 'datetime')]
    private $purchaseAt;

    #[ORM\OneToMany(mappedBy: 'Purchase', targetEntity: Purchasedetail::class, orphanRemoval: true)]
    private $purchasedetails;

    #[ORM\Column(type: 'string', length: 255)]
    private $status = "PENDING";

    #[ORM\Column(type: 'float')]
    private $total;

    public function __construct()
    {
        $this->purchasedetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->FullName;
    }

    public function setFullName(string $FullName): self
    {
        $this->FullName = $FullName;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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

    public function getPurchaseAt(): ?\DateTimeInterface
    {
        return $this->purchaseAt;
    }

    public function setPurchaseAt(\DateTimeInterface $purchaseAt): self
    {
        $this->purchaseAt = $purchaseAt;

        return $this;
    }

    /**
     * @return Collection<int, Purchasedetail>
     */
    public function getPurchasedetails(): Collection
    {
        return $this->purchasedetails;
    }

    public function addPurchasedetail(Purchasedetail $purchasedetail): self
    {
        if (!$this->purchasedetails->contains($purchasedetail)) {
            $this->purchasedetails[] = $purchasedetail;
            $purchasedetail->setPurchase($this);
        }

        return $this;
    }

    public function removePurchasedetail(Purchasedetail $purchasedetail): self
    {
        if ($this->purchasedetails->removeElement($purchasedetail)) {
            // set the owning side to null (unless already changed)
            if ($purchasedetail->getPurchase() === $this) {
                $purchasedetail->setPurchase(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
