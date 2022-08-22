<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AffiliateReferralViewRepository extends EntityRepository implements AffiliateReferralViewRepositoryInterface
{
    public function countViewsByAffiliate(AffiliateInterface $affiliate): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->innerJoin('o.affiliateReferral', 'ar')
            ->andWhere('ar.affiliate = :affiliate')
            ->setParameter('affiliate', $affiliate)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
