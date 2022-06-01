<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Odiseo\SyliusReferralsPlugin\Entity\CustomerPaymentInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

class CustomerPaymentRepository extends EntityRepository implements CustomerPaymentRepositoryInterface
{
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

    public function findByOrder(OrderInterface $order): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findNew(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->setParameter('state', CustomerPaymentInterface::STATE_NEW)
            ->getQuery()
            ->getResult()
        ;
    }

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
