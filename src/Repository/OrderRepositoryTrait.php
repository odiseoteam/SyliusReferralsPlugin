<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\OrderPaymentStates;

trait OrderRepositoryTrait
{
    abstract public function createQueryBuilder($alias, $indexBy = null);

    public function countSalesByAffiliate(AffiliateInterface $affiliate): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->innerJoin('o.affiliateReferral', 'ar')
            ->andWhere('ar.affiliate = :affiliate')
            ->andWhere('o.paymentState = :paymentState')
            ->setParameter('affiliate', $affiliate)
            ->setParameter('paymentState', OrderPaymentStates::STATE_PAID)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
