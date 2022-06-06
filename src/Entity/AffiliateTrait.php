<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

trait AffiliateTrait
{
    protected ?AffiliateInterface $affiliate = null;

    public function getAffiliate(): ?AffiliateInterface
    {
        return $this->affiliate;
    }

    public function setAffiliate(?AffiliateInterface $affiliate): void
    {
        $this->affiliate = $affiliate;
    }
}
