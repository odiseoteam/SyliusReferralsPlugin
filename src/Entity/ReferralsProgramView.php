<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class ReferralsProgramView implements ReferralsProgramViewInterface
{
    use TimestampableTrait;

    protected ?int $id = null;
    protected ?string $ip = null;
    protected ?CustomerInterface $customer = null;
    protected ?ReferralsProgramInterface $referralsProgram = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getReferralsProgram(): ?ReferralsProgramInterface
    {
        return $this->referralsProgram;
    }

    public function setReferralsProgram(?ReferralsProgramInterface $referralsProgram): void
    {
        $this->referralsProgram = $referralsProgram;
    }
}
