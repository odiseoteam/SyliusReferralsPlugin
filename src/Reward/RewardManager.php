<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Reward;

use Sylius\Component\Core\Model\OrderInterface;

final class RewardManager implements RewardManagerInterface
{
    public function __construct(
        private RewardHandlerInterface $rewardHandler,
    ) {
    }

    public function create(OrderInterface $order): void
    {
        $this->rewardHandler->apply($order);
    }
}
