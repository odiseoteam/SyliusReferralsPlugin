<?php

declare(strict_types=1);

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
