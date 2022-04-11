<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ReferralsProgramViewRepository extends EntityRepository
{
    public function findViewsByCustomer(CustomerInterface $customer): int
    {
        $results = $this->createQueryBuilder('view')
            ->innerJoin('view.referralsProgram', 'a', 'WITH', 'a.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getResult()
        ;

        return (int) count($results);
    }

    public function findMonthReferralsByCustomer(CustomerInterface $customer, \DateTimeInterface $dateTime): int
    {
        $results = $this->createQueryBuilder('view')
            ->innerJoin('view.referralsProgram', 'a', 'WITH', 'a.customer = :customer')
            ->andWhere('MONTH(view.createdAt) = :month')
            ->setParameter('customer', $customer)
            ->setParameter('month', date_format($dateTime, "m"))
            ->groupBy('view.customer, view.ip')
            ->getQuery()
            ->getResult()
        ;

        return (int) count($results);
    }
}
