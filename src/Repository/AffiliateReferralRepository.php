<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ProductInterface;

class AffiliateReferralRepository extends EntityRepository implements AffiliateReferralRepositoryInterface
{
    public function findOneByAffiliateNotExpired(AffiliateInterface $affiliate): ?AffiliateReferralInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.affiliate = :affiliate')
            ->andWhere('o.product IS NULL')
            ->andWhere('o.expiresAt IS NULL')
            ->setParameter('affiliate', $affiliate)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByAffiliateAndProductNotExpired(
        AffiliateInterface $affiliate,
        ProductInterface $product,
    ): ?AffiliateReferralInterface {
        return $this->createQueryBuilder('o')
            ->andWhere('o.affiliate = :affiliate')
            ->andWhere('o.product = :product')
            ->andWhere('o.expiresAt > :now')
            ->setParameter('product', $product)
            ->setParameter('affiliate', $affiliate)
            ->setParameter('now', new \DateTime())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
