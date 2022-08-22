<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AffiliateReferralRepositoryInterface extends RepositoryInterface
{
    public function findOneByAffiliateNotExpired(AffiliateInterface $affiliate): ?AffiliateReferralInterface;

    public function findOneByAffiliateAndProductNotExpired(
        AffiliateInterface $affiliate,
        ProductInterface $product
    ): ?AffiliateReferralInterface;
}
