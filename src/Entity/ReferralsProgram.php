<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramViewInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
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
     */
    private $tokenValue;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\Customer")
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\Product")
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
    public function getTokenValue(): ?string
    {
        return $this->tokenValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setTokenValue(string $tokenValue): void
    {
        $this->tokenValue = $tokenValue;
    }

    /**
     * {@inheritdoc}
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * {@inheritdoc}
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
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
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * {@inheritdoc}
     */
    public function setProduct(ProductInterface $product): void
    {
        $this->product = $product;
    }

    /**
     * {@inheritdoc}
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    /**
     * {@inheritdoc}
     */
    public function addView(?ReferralsProgramViewInterface $view): void
    {
        if (!$this->views->contains($view)) {
            $this->views->add($view);
            $view->setReferralsProgram($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeView(?ReferralsProgramViewInterface $view): void
    {
        if ($this->views->contains($view)) {
            $this->views->removeElement($view);
            $view->setReferralsProgram(null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addPayment(CustomerPaymentInterface $payment): void
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removePayment(CustomerPaymentInterface $payment): void
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
        }
    }    

    /**
     * {@inheritdoc}
     */
    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expireAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setExpireAt(\DateTimeInterface $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    /**
     * {@inheritdoc}
     */
    public function isExpired(): bool
    {
        $now = new \DateTime();

        return $now > $this->expireAt;
    }
}
