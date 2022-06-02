<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\OrderPaymentStates;

class ReferralsProgramRepository extends EntityRepository implements ReferralsProgramRepositoryInterface
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

    public function findSumViewsByCustomer(CustomerInterface $customer): ?int
    {
        return $this->createListByCustomerQueryBuilder($customer)
            ->select('COUNT(view.id) as count')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findReferredPageByCustomer(CustomerInterface $customer): ?ReferralsProgramInterface
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
        $referralsProgram = $this->findReferredPageByCustomer($customer);
        if (null === $referralsProgram || $referralsProgram->getViews()->count() === 0) {
            return null;
        }

        return $referralsProgram->getProduct();
    }

    public function findMaxViewReferredPageByCustomer(CustomerInterface $customer): int
    {
        $referralsProgram = $this->findReferredPageByCustomer($customer);
        if (null === $referralsProgram) {
            return 0;
        }

        return $referralsProgram->getViews()->count();
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
