<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Customer\Model\CustomerInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="odiseo_referrals_program_view")
 */
class ReferralsProgramView implements ReferralsProgramViewInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgram", inversedBy="views")
     * @ORM\JoinColumn(name="referrals_program_id", nullable=false)
     *
     * @var ReferralsProgramInterface|null
     */
    private $referralsProgram;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\Customer", cascade={"persist", "remove"})
     *
     * @var CustomerInterface|null
     */
    private $customer;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $ip;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @var \DateTimeInterface
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
    public function setReferralsProgram(?ReferralsProgramInterface $referralsProgram): void
    {
        $this->referralsProgram = $referralsProgram;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferralsProgram(): ?ReferralsProgramInterface
    {
        return $this->referralsProgram;
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
    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    /**
     * {@inheritdoc}
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * {@inheritdoc}
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
