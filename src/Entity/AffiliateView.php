<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class AffiliateView implements AffiliateViewInterface
{
    use TimestampableTrait;

    protected ?int $id = null;
    protected ?string $ip = null;
    protected ?CustomerInterface $customer = null;
    protected ?AffiliateInterface $affiliate = null;

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

    public function getAffiliate(): ?AffiliateInterface
    {
        return $this->affiliate;
    }

    public function setAffiliate(?AffiliateInterface $affiliate): void
    {
        $this->affiliate = $affiliate;
    }
}
