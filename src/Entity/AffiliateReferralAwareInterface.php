<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

interface AffiliateReferralAwareInterface
{
    public function getAffiliateReferral(): ?AffiliateReferralInterface;

    public function setAffiliateReferral(?AffiliateReferralInterface $affiliateReferral): void;
}
