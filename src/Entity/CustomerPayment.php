<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odiseo_customer_payment")
 */
class CustomerPayment implements CustomerPaymentInterface
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
     * @ORM\Column(type="string", name="currency_code", length="3")
     *
     * @var string|null
     */
    protected $currencyCode;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    protected $amount;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    protected $state = CustomerPaymentInterface::STATE_ON_HOLD;

    /**
     * @ORM\Column(type="json_array")
     *
     * @var array|null
     */
    protected $details = [];

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Order\Model\Order")
     *
     * @var OrderInterface|null
     */
    protected $order;

    /**
     * @ORM\ManyToMany(targetEntity="Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgram", mappedBy="payments")
     *
     * @var Collection
     */
    private $referralsPrograms;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\Customer")
     *
     * @var CustomerInterface|null
     */
    protected $customer;

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
        $this->referralsPrograms = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode(?string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * {@inheritdoc}
     */
    public function setDetails(array $details): void
    {
        $this->details = $details;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferralsPrograms(): Collection
    {
        return $this->referralsPrograms;
    }

    /**
     * {@inheritdoc}
     */
    public function addReferralsProgram(?ReferralsProgramInterface $referralsProgram): void
    {
        if (!$this->referralsPrograms->contains($referralsProgram)) {
            $this->referralsPrograms->add($referralsProgram);
            if (null !== $referralsProgram) {
                $referralsProgram->addPayment($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeReferralsProgram(?ReferralsProgramInterface $referralsProgram): void
    {
        if ($this->referralsPrograms->contains($referralsProgram)) {
            $this->referralsPrograms->removeElement($referralsProgram);
            if (null !== $referralsProgram) {
                $referralsProgram->removePayment($this);
            }
        }
    }
}
