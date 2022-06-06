<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

interface AffiliateAwareInterface
{
    public function getAffiliate(): ?AffiliateInterface;

    public function setAffiliate(?AffiliateInterface $affiliate): void;
}
