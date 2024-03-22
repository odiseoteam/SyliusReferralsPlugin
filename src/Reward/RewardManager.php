<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Reward;

use Sylius\Component\Core\Model\OrderInterface;

final class RewardManager implements RewardManagerInterface
{
    private RewardHandlerInterface $rewardHandler;

    public function __construct(
        RewardHandlerInterface $rewardHandler,
    ) {
        $this->rewardHandler = $rewardHandler;
    }

    public function create(OrderInterface $order): void
    {
        $this->rewardHandler->apply($order);
    }
}
