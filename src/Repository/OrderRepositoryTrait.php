<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\OrderPaymentStates;

trait OrderRepositoryTrait
{
    abstract public function createQueryBuilder($alias, $indexBy = null);

    public function countSalesByCustomer(CustomerInterface $customer): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->innerJoin('o.affiliate', 'a')
            ->andWhere('a.customer = :customer')
            ->andWhere('o.paymentState = :paymentState')
            ->setParameter('customer', $customer)
            ->setParameter('paymentState', OrderPaymentStates::STATE_PAID)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
