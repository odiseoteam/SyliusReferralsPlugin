<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\OrderPaymentStates;

class AffiliateRepository extends EntityRepository implements AffiliateRepositoryInterface
{
    public function createListByCustomerInnerQueryBuilder(CustomerInterface $customer): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.views', 'view')
            ->where('a.customer = :customer')
            ->andWhere('view.customer <> :customer')
            ->setParameter('customer', $customer)
        ;
    }

    public function createListByCustomerQueryBuilder(CustomerInterface $customer): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.views', 'view')
            ->where('a.customer = :customer')
            ->setParameter('customer', $customer)
        ;
    }

    public function findOneByCustomerNotExpired(CustomerInterface $customer): ?AffiliateInterface
    {
        return $this->createQueryBuilder('a')
            ->where('a.customer = :customer')
            ->andWhere('a.product IS NULL')
            ->andWhere('a.expiresAt IS NULL')
            ->setParameter('customer', $customer)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByCustomerAndProductNotExpired(
        CustomerInterface $customer,
        ProductInterface $product
    ): ?AffiliateInterface {
        return $this->createQueryBuilder('a')
            ->where('a.customer = :customer')
            ->andWhere('a.product = :product')
            ->andWhere('a.expiresAt > :now')
            ->setParameter('product', $product)
            ->setParameter('customer', $customer)
            ->setParameter('now', new \DateTime())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findSumViewsByCustomer(CustomerInterface $customer): ?int
    {
        return $this->createListByCustomerQueryBuilder($customer)
            ->select('COUNT(view.id) as count')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findReferredPageByCustomer(CustomerInterface $customer): ?AffiliateInterface
    {
        return $this->createListByCustomerQueryBuilder($customer)
            ->select('COUNT(view.id) as HIDDEN count, a')
            ->groupBy('a.id')
            ->orderBy('count', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findMaxProductReferredPageByCustomer(CustomerInterface $customer): ?ProductInterface
    {
        $affiliate = $this->findReferredPageByCustomer($customer);
        if (null === $affiliate || $affiliate->getViews()->count() === 0) {
            return null;
        }

        return $affiliate->getProduct();
    }

    public function findMaxViewReferredPageByCustomer(CustomerInterface $customer): int
    {
        $affiliate = $this->findReferredPageByCustomer($customer);
        if (null === $affiliate) {
            return 0;
        }

        return $affiliate->getViews()->count();
    }

    public function findCountSalesByCustomer(CustomerInterface $customer): int
    {
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->innerJoin('a.order', 'o')
            ->where('a.customer = :customer')
            ->setParameter('customer', $customer)
            ->andWhere('o.paymentState = :paymentState')
            ->setParameter('paymentState', OrderPaymentStates::STATE_PAID)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
