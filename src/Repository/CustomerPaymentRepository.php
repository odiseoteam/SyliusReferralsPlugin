<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Odiseo\SyliusReferralsPlugin\Entity\CustomerPaymentInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

class CustomerPaymentRepository extends EntityRepository implements CustomerPaymentRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createListByCustomerQueryBuilder(CustomerInterface $customer): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.order', 'u')
            ->andWhere('u.paymentState = :state')
            ->andWhere('o.customer = :customer')
            ->andWhere('o.amount != 0')
            ->setParameter('state', OrderPaymentStates::STATE_PAID)
            ->setParameter('customer', $customer)
            ->groupBy('o.id')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findByOrder(OrderInterface $order): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findNew(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->setParameter('state', CustomerPaymentInterface::STATE_NEW)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalPaymentsMade(): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('SUM(o.amount)')
            ->andWhere('o.state = :state')
            ->setParameter('state', CustomerPaymentInterface::STATE_COMPLETED)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalPaymentsPending(): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('SUM(o.amount)')
            ->andWhere('o.state = :state')
            ->setParameter('state', CustomerPaymentInterface::STATE_NEW)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
