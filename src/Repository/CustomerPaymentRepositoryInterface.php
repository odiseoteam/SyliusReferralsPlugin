<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface CustomerPaymentRepositoryInterface extends RepositoryInterface
{
    public function createListByCustomerQueryBuilder(CustomerInterface $customer): QueryBuilder;

    public function findByOrder(OrderInterface $order): array;

    public function findNew(): array;

    public function getTotalPaymentsMade(): int;

    public function getTotalPaymentsPending(): int;
}
