<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ReferralsProgramRepositoryInterface extends RepositoryInterface
{
    public function createListByCustomerInnerQueryBuilder(CustomerInterface $customer): QueryBuilder;

    public function createListByCustomerQueryBuilder(CustomerInterface $customer): QueryBuilder;

    public function findSumViewsByCustomer(CustomerInterface $customer): ?int;

    public function findReferredPageByCustomer(CustomerInterface $customer): ?ReferralsProgramInterface;

    public function findMaxProductReferredPageByCustomer(CustomerInterface $customer): ?ProductInterface;

    public function findMaxViewReferredPageByCustomer(CustomerInterface $customer): int;

    public function findCountSalesByCustomer(CustomerInterface $customer): int;
}
