<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;

class AffiliateReferralView implements AffiliateReferralViewInterface
{
    use TimestampableTrait;

    protected ?int $id = null;

    protected ?string $ip = null;

    protected ?AffiliateReferralInterface $affiliateReferral = null;

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

    public function getAffiliateReferral(): ?AffiliateReferralInterface
    {
        return $this->affiliateReferral;
    }

    public function setAffiliateReferral(?AffiliateReferralInterface $affiliateReferral): void
    {
        $this->affiliateReferral = $affiliateReferral;
    }
}
