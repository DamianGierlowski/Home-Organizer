<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Unique
     */
    private $bar_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $capacity;

    /**
     * @ORM\OneToMany(targetEntity=UsedProduct::class, mappedBy="product")
     */
    private $usedProducts;

    public function __construct()
    {
        $this->usedProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBarCode(): ?string
    {
        return $this->bar_code;
    }

    public function setBarCode(string $bar_code): self
    {
        $this->bar_code = $bar_code;

        return $this;
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

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCapacity(): ?float
    {
        return $this->capacity;
    }

    public function setCapacity(?float $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection|UsedProduct[]
     */
    public function getUsedProducts(): Collection
    {
        return $this->usedProducts;
    }

    public function addUsedProduct(UsedProduct $usedProduct): self
    {
        if (!$this->usedProducts->contains($usedProduct)) {
            $this->usedProducts[] = $usedProduct;
            $usedProduct->setProduct($this);
        }

        return $this;
    }

    public function removeUsedProduct(UsedProduct $usedProduct): self
    {
        if ($this->usedProducts->removeElement($usedProduct)) {
            // set the owning side to null (unless already changed)
            if ($usedProduct->getProduct() === $this) {
                $usedProduct->setProduct(null);
            }
        }

        return $this;
    }
}
