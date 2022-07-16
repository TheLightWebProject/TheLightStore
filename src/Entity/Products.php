<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *  @Assert\Length(
     *      min = 3,
     *      minMessage = "Product name must be at least {{ limit }} characters long!"
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Product name cannot contain a number!"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 4,
     *      minMessage = "Description must be at least {{ limit }} characters long!"
     * )
     */
    private $smallDesc;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 4,
     *      minMessage = "Description must be at least {{ limit }} characters long!"
     * )
     */
    private $detailDesc;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Brands::class, inversedBy="products")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=Suppliers::class, inversedBy="products")
     */
    private $supplier;

    /**
     * @ORM\OneToMany(targetEntity=Feedback::class, mappedBy="product")
     */
    private $feedback;

    /**
     * @ORM\OneToMany(targetEntity=OrderDetails::class, mappedBy="product")
     */
    private $orderDetails;

    /**
     * @ORM\OneToMany(targetEntity=CartDetails::class, mappedBy="products")
     */
    private $cartDetails;

    public function __construct()
    {
        $this->feedback = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
        $this->cartDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSmallDesc(): ?string
    {
        return $this->smallDesc;
    }

    public function setSmallDesc(string $smallDesc): self
    {
        $this->smallDesc = $smallDesc;

        return $this;
    }

    public function getDetailDesc(): ?string
    {
        return $this->detailDesc;
    }

    public function setDetailDesc(string $detailDesc): self
    {
        $this->detailDesc = $detailDesc;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBrand(): ?Brands
    {
        return $this->brand;
    }

    public function setBrand(?Brands $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getSupplier(): ?Suppliers
    {
        return $this->supplier;
    }

    public function setSupplier(?Suppliers $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback[] = $feedback;
            $feedback->setProduct($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getProduct() === $this) {
                $feedback->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setProduct($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProduct() === $this) {
                $orderDetail->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CartDetails>
     */
    public function getCartDetails(): Collection
    {
        return $this->cartDetails;
    }

    public function addCartDetail(CartDetails $cartDetail): self
    {
        if (!$this->cartDetails->contains($cartDetail)) {
            $this->cartDetails[] = $cartDetail;
            $cartDetail->setProducts($this);
        }

        return $this;
    }

    public function removeCartDetail(CartDetails $cartDetail): self
    {
        if ($this->cartDetails->removeElement($cartDetail)) {
            // set the owning side to null (unless already changed)
            if ($cartDetail->getProducts() === $this) {
                $cartDetail->setProducts(null);
            }
        }

        return $this;
    }
}
