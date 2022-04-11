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
use Odiseo\SyliusReferralsPlugin\Entity\CustomerPayment;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ReferralsProgramRepository extends EntityRepository implements RepositoryInterface
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

    public function findReferedPageByCustomer(CustomerInterface $customer): ?ReferralsProgramInterface
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

    public function findMaxProductReferedPageByCustomer(CustomerInterface $customer): ?ProductInterface
    {   
        $referralsProgram = $this->findReferedPageByCustomer($customer);
        if (!$referralsProgram || $referralsProgram->getViews()->count() === 0) {
            return null;
        }
        
        return $referralsProgram->getProduct();
    }

    public function findMaxViewReferedPageByCustomer(CustomerInterface $customer): int
    {
        $referralsProgram = $this->findReferedPageByCustomer($customer);
        if (!$referralsProgram) {
            return 0;
        }

        return (int) $referralsProgram->getViews()->count();
    }

    public function findCountPaymentsByCustomer(CustomerInterface $customer): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(payment)')
            ->where('a.customer = :customer')
            ->setParameter('customer', $customer)
            ->innerJoin('a.payments', 'payment')
            ->andWhere('payment.amount != 0')
            ->andWhere('payment.state IN (:state)')
            ->setParameter('state', [
                CustomerPayment::STATE_NEW,
                CustomerPayment::STATE_COMPLETED,
            ])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}