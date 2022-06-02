<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odiseo_referrals_program")
 */
class ReferralsProgram implements ReferralsProgramInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="token_value", nullable=false)
     *
     * @var string|null
     */
    private $tokenValue;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string|null
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     *
     * @var CustomerInterface|null
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Order\Model\OrderInterface")
     *
     * @var OrderInterface|null
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     *
     * @var ProductInterface|null
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramView", mappedBy="referralsProgram")
     *
     * @var Collection
     */
    private $views;

    /**
     * @ORM\ManyToMany(targetEntity="Odiseo\SyliusReferralsPlugin\Entity\CustomerPayment", inversedBy="referralsPrograms")
     * @ORM\JoinTable(name="odiseo_referrals_program_payments",
     *      joinColumns={@ORM\JoinColumn(name="referralsprogram_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="customerpayment_id", referencedColumnName="id", unique=true)}
     * )
     *
     * @var ArrayCollection|null
     */
    private $payments;

    /**
     * @ORM\Column(type="datetime", name="expire_at", nullable=false)
     *
     * @var \DateTimeInterface|null
     */
    protected $expireAt;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     *
     * @var \DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     *
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->expireAt = new \DateTime();
        $this->expireAt->modify("+15 day");

        $this->payments = new ArrayCollection();
        $this->views = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenValue(): ?string
    {
        return $this->tokenValue;
    }

    public function setTokenValue(string $tokenValue): void
    {
        $this->tokenValue = $tokenValue;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(ProductInterface $product): void
    {
        $this->product = $product;
    }

    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(?ReferralsProgramViewInterface $view): void
    {
        if (!$this->views->contains($view)) {
            $this->views->add($view);
            if (null !== $view) {
                $view->setReferralsProgram($this);
            }
        }
    }

    public function removeView(?ReferralsProgramViewInterface $view): void
    {
        if ($this->views->contains($view)) {
            $this->views->removeElement($view);
            if (null !== $view) {
                $view->setReferralsProgram(null);
            }
        }
    }

    public function addPayment(CustomerPaymentInterface $payment): void
    {
        if (null !== $this->payments) {
            if (!$this->payments->contains($payment)) {
                $this->payments->add($payment);
            }
        }
    }

    public function removePayment(CustomerPaymentInterface $payment): void
    {
        if (null !== $this->payments) {
            if ($this->payments->contains($payment)) {
                $this->payments->removeElement($payment);
            }
        }
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTimeInterface $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    public function isExpired(): bool
    {
        $now = new \DateTime();

        return $now > $this->expireAt;
    }
}
