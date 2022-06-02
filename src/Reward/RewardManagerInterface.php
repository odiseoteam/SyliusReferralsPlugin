<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Reward;

use Sylius\Component\Core\Model\OrderInterface;

interface RewardManagerInterface
{
    public function create(OrderInterface $order): void;
}
