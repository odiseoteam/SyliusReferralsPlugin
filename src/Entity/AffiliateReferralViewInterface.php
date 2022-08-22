<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AffiliateReferralViewInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public function setIp(?string $ip): void;

    public function getIp(): ?string;

    public function setAffiliateReferral(?AffiliateReferralInterface $affiliateReferral): void;

    public function getAffiliateReferral(): ?AffiliateReferralInterface;
}
