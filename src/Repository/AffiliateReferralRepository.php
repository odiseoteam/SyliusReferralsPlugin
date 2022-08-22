<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AffiliateReferralRepository extends EntityRepository implements AffiliateReferralRepositoryInterface
{
    public function findOneByCustomerNotExpired(CustomerInterface $customer): ?AffiliateReferralInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.customer = :customer')
            ->andWhere('o.product IS NULL')
            ->andWhere('o.expiresAt IS NULL')
            ->setParameter('customer', $customer)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByCustomerAndProductNotExpired(
        CustomerInterface $customer,
        ProductInterface $product
    ): ?AffiliateReferralInterface {
        return $this->createQueryBuilder('o')
            ->andWhere('o.customer = :customer')
            ->andWhere('o.product = :product')
            ->andWhere('o.expiresAt > :now')
            ->setParameter('product', $product)
            ->setParameter('customer', $customer)
            ->setParameter('now', new \DateTime())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
