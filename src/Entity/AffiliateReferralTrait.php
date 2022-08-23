<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

trait AffiliateReferralTrait
{
    protected ?AffiliateReferralInterface $affiliateReferral = null;

    public function getAffiliateReferral(): ?AffiliateReferralInterface
    {
        return $this->affiliateReferral;
    }

    public function setAffiliateReferral(?AffiliateReferralInterface $affiliateReferral): void
    {
        $this->affiliateReferral = $affiliateReferral;
    }
}
