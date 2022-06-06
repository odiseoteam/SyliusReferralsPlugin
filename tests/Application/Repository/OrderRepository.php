<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReferralsPlugin\Application\Repository;

use Odiseo\SyliusReferralsPlugin\Repository\OrderRepositoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\OrderRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository as BaseOrderRepository;

class OrderRepository extends BaseOrderRepository implements OrderRepositoryInterface
{
    use OrderRepositoryTrait;
}
