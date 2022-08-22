<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AffiliateReferralViewRepositoryInterface extends RepositoryInterface
{
    public function countViewsByAffiliate(AffiliateInterface $affiliate): int;
}
