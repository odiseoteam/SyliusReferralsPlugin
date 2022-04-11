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
use Sylius\Component\Core\Model\OrderInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface CustomerPaymentRepositoryInterface extends RepositoryInterface
{
    /**
     * @param CustomerInterface $customer
     *
     * @return QueryBuilder
     */
    public function createListByCustomerQueryBuilder(CustomerInterface $customer): QueryBuilder;

    /**
     * @param ORderInterface $order
     *
     * @return array
     */
    public function findByOrder(OrderInterface $order): array;

    /**
     * @return array
     */
    public function findNew(): array;

    /**
     * @return int
     */
    public function getTotalPaymentsMade(): int;

    /**
     * @return int
     */
    public function getTotalPaymentsPending(): int;
}
