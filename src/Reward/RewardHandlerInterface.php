<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Reward;

use Sylius\Component\Core\Model\OrderInterface;

interface RewardHandlerInterface
{
    public function apply(OrderInterface $order): void;
}
